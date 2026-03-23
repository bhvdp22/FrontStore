<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payout;
use App\Models\Order;
use App\Models\User;
use App\Models\Campaign;
use Carbon\Carbon;

class AdminPayoutController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');

        $payouts = Payout::with('seller')
            ->when($status !== 'all', function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->get();

        // Summary stats
        $pendingCount = Payout::where('status', 'pending')->count();
        $pendingAmount = Payout::where('status', 'pending')->sum('net_amount');
        $completedCount = Payout::where('status', 'completed')->count();
        $completedAmount = Payout::where('status', 'completed')->sum('net_amount');

        return view('admin.payouts.index', compact(
            'payouts', 'status', 'pendingCount', 'pendingAmount',
            'completedCount', 'completedAmount'
        ));
    }

    public function show($id)
    {
        $payout = Payout::with('seller')->findOrFail($id);

        // Get orders in this period for this seller
        $orders = Order::where('seller_id', $payout->seller_id)
            ->where('status', 'Delivered')
            ->whereBetween('updated_at', [
                $payout->period_start->startOfDay(),
                $payout->period_end->endOfDay()
            ])
            ->orderByDesc('updated_at')
            ->get();

        // Get campaign deductions
        $campaigns = Campaign::whereHas('product', function ($q) use ($payout) {
            $q->where('seller_id', $payout->seller_id);
        })->get();

        return view('admin.payouts.show', compact('payout', 'orders', 'campaigns'));
    }

    public function approve(Request $request, $id)
    {
        $payout = Payout::findOrFail($id);

        if ($payout->status !== 'pending') {
            return back()->with('error', 'This payout is not in pending status.');
        }

        // Validate the payout amount against actual earnings
        $payableBalance = Payout::getPayableBalance($payout->seller_id);

        // Allow approval (admin can add notes)
        $payout->status = 'approved';
        $payout->approved_at = now();
        $payout->approved_by = auth('admin')->id();
        $payout->admin_notes = $request->input('admin_notes');
        $payout->save();

        // Notify seller: payout approved (with bank details)
        \App\Services\NotificationService::payoutApprovedForSeller(
            $payout->seller_id, $payout->net_amount, $payout->payout_id,
            $payout->bank_name ?? '', $payout->bank_account ?? '', $payout->ifsc_code ?? ''
        );

        return back()->with('success', 'Payout approved! Amount: ₹' . number_format($payout->net_amount, 2));
    }

    public function reject(Request $request, $id)
    {
        $payout = Payout::findOrFail($id);

        if ($payout->status !== 'pending') {
            return back()->with('error', 'This payout is not in pending status.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $payout->status = 'rejected';
        $payout->rejected_at = now();
        $payout->rejection_reason = $request->input('rejection_reason');
        $payout->save();

        // Notify seller: payout rejected (with bank context)
        \App\Services\NotificationService::payoutRejectedForSeller(
            $payout->seller_id, $payout->net_amount, $payout->rejection_reason,
            $payout->bank_name ?? '', $payout->bank_account ?? ''
        );

        return back()->with('success', 'Payout rejected.');
    }

    public function complete(Request $request, $id)
    {
        $payout = Payout::findOrFail($id);

        if ($payout->status !== 'approved') {
            return back()->with('error', 'This payout must be approved first.');
        }

        $request->validate([
            'transaction_reference' => 'nullable|string|max:255',
        ]);

        // Auto-generate transaction reference if not provided
        $txnRef = $request->input('transaction_reference');
        if (empty(trim($txnRef ?? ''))) {
            $txnRef = 'TXN-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        }

        $payout->status = 'completed';
        $payout->completed_at = now();
        $payout->transaction_reference = $txnRef;
        $payout->admin_notes = $request->input('admin_notes', $payout->admin_notes);
        $payout->save();

        // Notify seller: payout completed (money sent — with bank details)
        \App\Services\NotificationService::payoutCompletedForSeller(
            $payout->seller_id, $payout->net_amount, $payout->transaction_reference,
            $payout->bank_name ?? '', $payout->bank_account ?? '', $payout->ifsc_code ?? ''
        );

        return back()->with('success', 'Payout completed! Transaction ref: ' . $payout->transaction_reference);
    }
}
