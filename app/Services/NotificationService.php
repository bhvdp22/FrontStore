<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Admin;

class NotificationService
{
    // ─── NEW ORDER ───────────────────────────────────────────────

    /**
     * Notify seller: "New Order Received! Ship it fast."
     */
    public static function newOrderForSeller(int $sellerId, string $orderId, string $productName, float $amount): void
    {
        Notification::create([
            'type' => 'order',
            'title' => 'New Order Received!',
            'message' => "Order {$orderId} for \"{$productName}\" — ₹" . number_format($amount, 2) . ". Ship it fast!",
            'icon' => 'success',
            'action_url' => route('orders.index'),
            'recipient_type' => 'seller',
            'recipient_id' => $sellerId,
        ]);
    }

    /**
     * Notify all admins: "New Sale on Platform (Commission Earned)."
     */
    public static function newOrderForAdmin(string $orderId, string $productName, float $commission): void
    {
        $admins = Admin::all();
        foreach ($admins as $admin) {
            Notification::create([
                'type' => 'order',
                'title' => 'New Sale on Platform!',
                'message' => "Order {$orderId} — \"{$productName}\". Commission earned: ₹" . number_format($commission, 2) . ".",
                'icon' => 'success',
                'action_url' => route('admin.orders'),
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
            ]);
        }
    }

    // ─── PAYOUT REQUEST ──────────────────────────────────────────

    /**
     * Notify all admins: Seller requested a payout/withdrawal
     */
    public static function payoutRequestForAdmin(string $sellerName, float $amount, string $payoutId): void
    {
        $admins = Admin::all();
        foreach ($admins as $admin) {
            Notification::create([
                'type' => 'payout',
                'title' => 'Payout Request!',
                'message' => "{$sellerName} needs payment — ₹" . number_format($amount, 2) . ". Review and process.",
                'icon' => 'warning',
                'action_url' => route('admin.payouts'),
                'recipient_type' => 'admin',
                'recipient_id' => $admin->id,
            ]);
        }
    }

    // ─── PAYOUT PROCESSED ────────────────────────────────────────

    /**
     * Notify seller: Payout approved — includes bank details so seller knows where money is going
     */
    public static function payoutApprovedForSeller(
        int $sellerId, float $amount, string $payoutRef,
        string $bankName = '', string $bankAccount = '', string $ifscCode = ''
    ): void {
        $bankInfo = '';
        if ($bankName && $bankAccount) {
            $masked = self::maskAccountNumber($bankAccount);
            $bankInfo = " Payment to {$bankName} A/c {$masked} (IFSC: {$ifscCode}).";
        }

        Notification::create([
            'type' => 'payout',
            'title' => 'Payout Approved! ✓',
            'message' => "Your payout of ₹" . number_format($amount, 2) . " ({$payoutRef}) has been approved.{$bankInfo} Money is on its way!",
            'icon' => 'success',
            'action_url' => route('payments.index'),
            'recipient_type' => 'seller',
            'recipient_id' => $sellerId,
        ]);
    }

    /**
     * Notify seller: Payout completed — money sent to bank with full details
     */
    public static function payoutCompletedForSeller(
        int $sellerId, float $amount, string $txnRef,
        string $bankName = '', string $bankAccount = '', string $ifscCode = ''
    ): void {
        $bankInfo = '';
        if ($bankName && $bankAccount) {
            $masked = self::maskAccountNumber($bankAccount);
            $bankInfo = " → {$bankName} A/c {$masked} (IFSC: {$ifscCode})";
        }

        Notification::create([
            'type' => 'payout',
            'title' => 'Money Sent! ₹' . number_format($amount, 2),
            'message' => "₹" . number_format($amount, 2) . " has been transferred{$bankInfo}. Transaction Ref: {$txnRef}. Please check your bank statement.",
            'icon' => 'success',
            'action_url' => route('payments.index'),
            'recipient_type' => 'seller',
            'recipient_id' => $sellerId,
        ]);
    }

    /**
     * Notify seller: Payout rejected — with bank details for context
     */
    public static function payoutRejectedForSeller(
        int $sellerId, float $amount, string $reason,
        string $bankName = '', string $bankAccount = ''
    ): void {
        $bankInfo = '';
        if ($bankName && $bankAccount) {
            $masked = self::maskAccountNumber($bankAccount);
            $bankInfo = " (Bank: {$bankName}, A/c {$masked})";
        }

        Notification::create([
            'type' => 'payout',
            'title' => 'Payout Rejected',
            'message' => "Your payout of ₹" . number_format($amount, 2) . "{$bankInfo} was rejected. Reason: {$reason}",
            'icon' => 'danger',
            'action_url' => route('payments.index'),
            'recipient_type' => 'seller',
            'recipient_id' => $sellerId,
        ]);
    }

    // ─── AD PAUSED ───────────────────────────────────────────────

    /**
     * Notify seller: Ad campaign paused due to low balance
     */
    public static function adPausedLowBalance(int $sellerId, string $campaignName): void
    {
        Notification::create([
            'type' => 'ad',
            'title' => 'Ad Campaign Paused!',
            'message' => "Alert: Your ad \"{$campaignName}\" stopped due to low funds. Get more delivered orders or reduce ad budget.",
            'icon' => 'danger',
            'action_url' => route('ads.index'),
            'recipient_type' => 'seller',
            'recipient_id' => $sellerId,
        ]);
    }

    // ─── LOW STOCK ───────────────────────────────────────────────

    /**
     * Notify seller: Product stock running low (< 5)
     */
    public static function lowStockWarning(int $sellerId, string $productName, int $currentQty, int $productId): void
    {
        Notification::create([
            'type' => 'stock',
            'title' => 'Stock Running Low!',
            'message' => "\"{$productName}\" has only {$currentQty} units left. Add more quantity before it goes out of stock!",
            'icon' => 'warning',
            'action_url' => route('products.edit', $productId),
            'recipient_type' => 'seller',
            'recipient_id' => $sellerId,
        ]);
    }

    // ─── HELPERS ─────────────────────────────────────────────────

    /**
     * Mask bank account number: 37051234567161 → 3705****7161
     */
    public static function maskAccountNumber(string $account): string
    {
        $len = strlen($account);
        if ($len <= 8) return $account;
        return substr($account, 0, 4) . str_repeat('*', $len - 8) . substr($account, -4);
    }

    /**
     * Get unread count for a seller
     */
    public static function unreadCountForSeller(int $sellerId): int
    {
        return Notification::forSeller($sellerId)->unread()->count();
    }

    /**
     * Get unread count for an admin
     */
    public static function unreadCountForAdmin(int $adminId): int
    {
        return Notification::forAdmin($adminId)->unread()->count();
    }
}
