<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;

class AdController extends Controller
{
    public function index(){
        $seller = $this->currentSeller();

        // Auto-expire campaigns that passed their end date
        Campaign::where('end_date', '<', Carbon::today())
            ->where('status', '!=', 'Ended')
            ->update(['status' => 'Ended']);

        // Backfill missed daily deductions for this seller's campaigns
        // (handles the case where the scheduler wasn't running)
        if ($seller) {
            $this->backfillMissedDeductions($seller->id);
        }
        
        $campaigns = Campaign::when($seller, function($q) use ($seller) {
                $q->whereHas('product', function($productQuery) use ($seller) {
                    $productQuery->where('seller_id', $seller->id);
                });
            })
            ->orderByDesc('created_at')
            ->get();

        // Calculate seller's available balance
        $balance = $seller ? Campaign::getSellerBalance($seller->id) : 0;

        // Total ad spend across all campaigns
        $totalAdSpend = $seller ? Campaign::whereHas('product', function($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->sum('total_deducted') : 0;

        // Total earnings
        $totalEarnings = $seller ? Order::where('seller_id', $seller->id)
            ->where('status', 'Delivered')
            ->sum('seller_earnings') : 0;

        return view('ads.index', compact('campaigns', 'balance', 'totalAdSpend', 'totalEarnings'));
    }

    public function create() {
        $seller = $this->currentSeller();
        
        // Check if seller account is active
        if (!$seller || $seller->status !== 'active') {
            return redirect()->route('ads.index')
                ->with('error', 'You cannot create ad campaigns until your account is approved by admin.');
        }

        $balance = Campaign::getSellerBalance($seller->id);
        
        $products = Product::where('quantity', '>', 0)
            ->when($seller, function($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })
            ->get();
            
        return view('ads.create', compact('products', 'balance'));
    }

    public function store(Request $request) {
        $seller = $this->currentSeller();
        
        // Check if seller account is active
        if (!$seller || $seller->status !== 'active') {
            return redirect()->route('ads.index')
                ->with('error', 'You cannot create ad campaigns until your account is approved by admin.');
        }
        
        $data = $request->validate([
            'campaign_name' => 'required|string',
            'sku' => 'required|string',
            'daily_budget' => 'required|numeric|min:50',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Verify the product belongs to the current seller
        $product = Product::where('sku', $data['sku'])
            ->when($seller, function($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })
            ->first();

        if (!$product) {
            return back()->withErrors(['sku' => 'You can only advertise your own products.'])->withInput();
        }

        // Check if seller has enough balance for at least one day
        $balance = Campaign::getSellerBalance($seller->id);
        if ($balance < $data['daily_budget']) {
            return back()->with('error', 'Insufficient balance! Your available balance is ₹' . number_format($balance, 2) . '. You need at least ₹' . number_format($data['daily_budget'], 2) . ' (1 day budget). Get more orders delivered to increase your balance.')->withInput();
        }

        $data['status'] = 'Active';
        $data['total_deducted'] = 0;
        Campaign::create($data);
        return redirect()->route('ads.index')->with('success', 'Ad campaign created successfully.');
    }

    public function toggleStatus($id)
    {
        $campaign = $this->findOwnedCampaign($id);
        $seller = $this->currentSeller();

        // Do not resume campaigns that have ended by date
        if (Carbon::parse($campaign->end_date)->lt(Carbon::today())) {
            $campaign->status = 'Ended';
            $campaign->save();
            return back()->with('error', 'Campaign has already ended.');
        }
        
        if ($campaign->status == 'Active') {
            $campaign->status = 'Paused';
            $message = 'Campaign paused.';
        } else {
            // Check balance before resuming
            $balance = Campaign::getSellerBalance($seller->id);
            if ($balance < $campaign->daily_budget) {
                return back()->with('error', 'Cannot resume — insufficient balance (₹' . number_format($balance, 2) . '). You need at least ₹' . number_format($campaign->daily_budget, 2) . '/day.');
            }
            $campaign->status = 'Active';
            $message = 'Campaign resumed!';
        }
        $campaign->save();
        return back()->with('success', $message);
    }

    public function destroy($id) {
        $campaign = $this->findOwnedCampaign($id);
        $campaign->delete();
        return redirect()->route('ads.index')->with('success', 'Ad campaign deleted successfully.');
    }

    private function currentSeller(): ?User
    {
        $username = session('loginusername');
        return $username ? User::where('name', $username)->first() : null;
    }

    private function findOwnedCampaign($id): Campaign
    {
        $seller = $this->currentSeller();
        
        $campaign = Campaign::where('id', $id)
            ->when($seller, function($q) use ($seller) {
                $q->whereHas('product', function($productQuery) use ($seller) {
                    $productQuery->where('seller_id', $seller->id);
                });
            })
            ->first();

        if (!$campaign) {
            abort(403, 'You are not allowed to access this campaign.');
        }

        return $campaign;
    }

    /**
     * Backfill missed daily deductions for campaigns where the scheduler didn't run.
     */
    private function backfillMissedDeductions(int $sellerId): void
    {
        $today = Carbon::today();

        // Get all campaigns for this seller that may have missed deductions
        // (Active or Ended campaigns that haven't been fully deducted)
        $campaigns = Campaign::whereIn('status', ['Active', 'Ended', 'Paused'])
            ->whereHas('product', function ($q) use ($sellerId) {
                $q->where('seller_id', $sellerId);
            })
            ->with('product')
            ->get();

        foreach ($campaigns as $campaign) {
            if (!$campaign->product) {
                continue;
            }

            // Determine the first day that needs deduction
            $fromDate = $campaign->last_deducted_at
                ? Carbon::parse($campaign->last_deducted_at)->addDay()
                : Carbon::parse($campaign->start_date);

            // Determine the last day that needs deduction (end_date or today, whichever is earlier)
            $toDate = Carbon::parse($campaign->end_date)->min($today);

            // Skip if nothing to backfill
            if ($fromDate->gt($toDate)) {
                continue;
            }

            $currentDate = $fromDate->copy();
            while ($currentDate->lte($toDate)) {
                $balance = Campaign::getSellerBalance($sellerId);

                if ($balance >= $campaign->daily_budget) {
                    $campaign->total_deducted += $campaign->daily_budget;
                    $campaign->last_deducted_at = $currentDate->toDateString();
                    $campaign->save();
                } else {
                    // Not enough balance — stop backfilling for this campaign
                    break;
                }

                $currentDate->addDay();
            }
        }
    }
}
