<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Campaign;
use App\Services\NotificationService;
use Carbon\Carbon;

class GeneratePayoutRequests extends Command
{
    protected $signature = 'payouts:generate-weekly';
    protected $description = 'Auto-generate payout requests every 7 days for sellers with payable balance';

    public function handle()
    {
        $today = Carbon::today();

        // Get all active sellers
        $sellers = User::where('status', 'active')->get();

        $created = 0;
        $skipped = 0;

        foreach ($sellers as $seller) {
            // Check if there's already a pending/approved payout for this seller
            $pendingPayout = Payout::where('seller_id', $seller->id)
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($pendingPayout) {
                $skipped++;
                continue;
            }

            // Determine the period: last payout end_date+1 to today
            $lastCompletedPayout = Payout::where('seller_id', $seller->id)
                ->where('status', 'completed')
                ->orderByDesc('period_end')
                ->first();

            if ($lastCompletedPayout) {
                $periodStart = Carbon::parse($lastCompletedPayout->period_end)->addDay();
                // Only generate if 7 days have passed since last payout
                if ($periodStart->diffInDays($today) < 7) {
                    $skipped++;
                    continue;
                }
            } else {
                // First payout — start from seller's first delivered order
                $firstOrder = Order::where('seller_id', $seller->id)
                    ->where('status', 'Delivered')
                    ->orderBy('updated_at')
                    ->first();

                if (!$firstOrder) {
                    $skipped++;
                    continue;
                }

                $periodStart = Carbon::parse($firstOrder->updated_at)->startOfDay();

                // Only generate if 7 days have passed since first order
                if ($periodStart->diffInDays($today) < 7) {
                    $skipped++;
                    continue;
                }
            }

            $periodEnd = $today->copy();

            // Calculate payable balance
            $payableBalance = Payout::getPayableBalance($seller->id);

            if ($payableBalance <= 0) {
                $skipped++;
                continue;
            }

            // Get ad deductions during this period
            $totalAdSpend = (float) Campaign::whereHas('product', function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })->sum('total_deducted');

            $totalPaidOut = (float) Payout::where('seller_id', $seller->id)
                ->whereIn('status', ['approved', 'completed'])
                ->sum('net_amount');

            $totalEarnings = (float) Order::where('seller_id', $seller->id)
                ->where('status', 'Delivered')
                ->sum('seller_earnings');

            $grossAmount = $totalEarnings - $totalPaidOut;
            $adDeductions = $totalAdSpend; // deductions are already tracked
            $netAmount = max(0, $grossAmount - $adDeductions);

            if ($netAmount <= 0) {
                $skipped++;
                continue;
            }

            // Create payout request with seller's bank details
            Payout::create([
                'payout_id' => Payout::generatePayoutId(),
                'seller_id' => $seller->id,
                'amount' => $grossAmount,
                'ad_deductions' => $adDeductions,
                'net_amount' => $netAmount,
                'status' => 'pending',
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'bank_name' => $seller->bank_name,
                'bank_account' => $seller->bank_account,
                'ifsc_code' => $seller->ifsc_code,
            ]);

            $created++;
            $this->info("Payout created for seller '{$seller->name}': ₹" . number_format($netAmount, 2));

            // Notify all admins: new payout request
            NotificationService::payoutRequestForAdmin(
                $seller->name, $netAmount, Payout::generatePayoutId()
            );
        }

        $this->info("Weekly payout generation complete: {$created} created, {$skipped} skipped.");
        return 0;
    }
}
