<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;
use App\Models\Payout;
use App\Models\Order;
use App\Models\Campaign;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    /**
     * Get notifications for seller (JSON for bell dropdown)
     */
    public function sellerNotifications()
    {
        $seller = $this->currentSeller();
        if (!$seller) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $notifications = Notification::forSeller($seller->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $unreadCount = Notification::forSeller($seller->id)->unread()->count();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'icon' => $n->icon,
                    'action_url' => $n->action_url,
                    'is_read' => $n->is_read,
                    'time_ago' => $n->created_at->diffForHumans(),
                    'created_at' => $n->created_at->format('M d, h:i A'),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get notifications for admin (JSON for bell dropdown)
     */
    public function adminNotifications()
    {
        $admin = auth('admin')->user();
        if (!$admin) {
            return response()->json(['notifications' => [], 'unread_count' => 0]);
        }

        $notifications = Notification::forAdmin($admin->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        $unreadCount = Notification::forAdmin($admin->id)->unread()->count();

        return response()->json([
            'notifications' => $notifications->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'icon' => $n->icon,
                    'action_url' => $n->action_url,
                    'is_read' => $n->is_read,
                    'time_ago' => $n->created_at->diffForHumans(),
                    'created_at' => $n->created_at->format('M d, h:i A'),
                ];
            }),
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Mark all as read for seller
     */
    public function markAllReadSeller()
    {
        $seller = $this->currentSeller();
        if ($seller) {
            Notification::forSeller($seller->id)->unread()->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
        return response()->json(['success' => true]);
    }

    /**
     * Mark all as read for admin
     */
    public function markAllReadAdmin()
    {
        $admin = auth('admin')->user();
        if ($admin) {
            Notification::forAdmin($admin->id)->unread()->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
        return response()->json(['success' => true]);
    }

    private function currentSeller(): ?User
    {
        $username = session('loginusername');
        return $username ? User::where('name', $username)->first() : null;
    }

    /**
     * Seller requests a manual withdrawal (creates a payout request)
     */
    public function sellerWithdraw(Request $request)
    {
        $seller = $this->currentSeller();
        if (!$seller) {
            return back()->with('error', 'Please log in first.');
        }

        // Check if there's already a pending/approved payout
        $existingPayout = Payout::where('seller_id', $seller->id)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingPayout) {
            return back()->with('error', 'You already have a pending payout request ('. $existingPayout->payout_id .'). Please wait for it to be processed.');
        }

        // Calculate payable balance
        $payableBalance = Payout::getPayableBalance($seller->id);

        if ($payableBalance <= 0) {
            return back()->with('error', 'No payable balance available for withdrawal.');
        }

        // Check minimum withdrawal (₹100)
        if ($payableBalance < 100) {
            return back()->with('error', 'Minimum withdrawal amount is ₹100. Your current balance is ₹' . number_format($payableBalance, 2));
        }

        // Check bank details
        if (empty($seller->bank_name) || empty($seller->bank_account) || empty($seller->ifsc_code)) {
            return back()->with('error', 'Please update your bank details in your profile before requesting a withdrawal.');
        }

        // Calculate period
        $lastPayout = Payout::where('seller_id', $seller->id)
            ->where('status', 'completed')
            ->orderByDesc('period_end')
            ->first();

        $periodStart = $lastPayout
            ? \Carbon\Carbon::parse($lastPayout->period_end)->addDay()
            : Order::where('seller_id', $seller->id)
                ->where('status', 'Delivered')
                ->orderBy('updated_at')
                ->value('updated_at') ?? now();

        // Get ad deductions
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
        $netAmount = max(0, $grossAmount - $totalAdSpend);

        if ($netAmount <= 0) {
            return back()->with('error', 'No payable balance after deductions.');
        }

        $payout = Payout::create([
            'payout_id' => Payout::generatePayoutId(),
            'seller_id' => $seller->id,
            'amount' => $grossAmount,
            'ad_deductions' => $totalAdSpend,
            'net_amount' => $netAmount,
            'status' => 'pending',
            'period_start' => \Carbon\Carbon::parse($periodStart)->toDateString(),
            'period_end' => now()->toDateString(),
            'bank_name' => $seller->bank_name,
            'bank_account' => $seller->bank_account,
            'ifsc_code' => $seller->ifsc_code,
        ]);

        // Notify admin about new withdrawal request
        NotificationService::payoutRequestForAdmin($seller->name, $netAmount, $payout->payout_id);

        return back()->with('success', 'Withdrawal request submitted! Payout ID: ' . $payout->payout_id . ' — Amount: ₹' . number_format($netAmount, 2));
    }
}
