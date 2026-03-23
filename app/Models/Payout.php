<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $payout_id
 * @property int $seller_id
 * @property float $amount
 * @property float $ad_deductions
 * @property float $net_amount
 * @property string $status
 * @property \Illuminate\Support\Carbon $period_start
 * @property \Illuminate\Support\Carbon $period_end
 * @property string|null $bank_name
 * @property string|null $bank_account
 * @property string|null $ifsc_code
 * @property string|null $transaction_reference
 * @property string|null $admin_notes
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $approved_at
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $rejected_at
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_id',
        'seller_id',
        'amount',
        'ad_deductions',
        'net_amount',
        'status',
        'period_start',
        'period_end',
        'bank_name',
        'bank_account',
        'ifsc_code',
        'transaction_reference',
        'admin_notes',
        'rejection_reason',
        'approved_at',
        'completed_at',
        'rejected_at',
        'approved_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'ad_deductions' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function approvedByAdmin()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Generate next payout ID like PAY-20260217-00001
     */
    public static function generatePayoutId(): string
    {
        $today = now()->format('Ymd');
        $lastPayout = self::where('payout_id', 'like', "PAY-{$today}-%")
            ->orderByDesc('id')
            ->first();

        if ($lastPayout) {
            $lastNumber = (int) substr($lastPayout->payout_id, -5);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return "PAY-{$today}-" . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get seller's earnings for a specific period
     */
    public static function getSellerEarningsForPeriod(int $sellerId, string $startDate, string $endDate): float
    {
        return (float) Order::where('seller_id', $sellerId)
            ->where('status', 'Delivered')
            ->whereBetween('updated_at', [$startDate, $endDate . ' 23:59:59'])
            ->sum('seller_earnings');
    }

    /**
     * Get ad deductions for a seller during a period
     */
    public static function getAdDeductionsForPeriod(int $sellerId, string $startDate, string $endDate): float
    {
        return (float) Campaign::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })
        ->where('last_deducted_at', '>=', $startDate)
        ->where('last_deducted_at', '<=', $endDate)
        ->sum('total_deducted');
    }

    /**
     * Get total payable balance for seller (all-time delivered earnings minus all-time ad spend minus already paid out)
     */
    public static function getPayableBalance(int $sellerId): float
    {
        $totalEarnings = (float) Order::where('seller_id', $sellerId)
            ->where('status', 'Delivered')
            ->sum('seller_earnings');

        $totalAdSpend = (float) Campaign::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->sum('total_deducted');

        $totalPaidOut = (float) self::where('seller_id', $sellerId)
            ->whereIn('status', ['approved', 'completed'])
            ->sum('net_amount');

        return max(0, $totalEarnings - $totalAdSpend - $totalPaidOut);
    }

    /**
     * Get next payout date (next 7-day cycle)
     */
    public static function getNextPayoutDate(int $sellerId): ?string
    {
        // Find the last payout for this seller
        $lastPayout = self::where('seller_id', $sellerId)
            ->orderByDesc('period_end')
            ->first();

        if ($lastPayout) {
            return $lastPayout->period_end->addDays(7)->format('M d, Y');
        }

        // If no payout yet, next payout is 7 days from seller's first delivered order
        $firstOrder = Order::where('seller_id', $sellerId)
            ->where('status', 'Delivered')
            ->orderBy('updated_at')
            ->first();

        if ($firstOrder) {
            $firstDate = \Carbon\Carbon::parse($firstOrder->updated_at);
            // Find next 7-day boundary
            $daysSince = $firstDate->diffInDays(now());
            $daysUntilNext = 7 - ($daysSince % 7);
            return now()->addDays($daysUntilNext)->format('M d, Y');
        }

        return null;
    }
}
