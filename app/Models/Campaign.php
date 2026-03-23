<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property string $campaign_name
 * @property string $sku
 * @property float $daily_budget
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property string $status
 * @property float $total_deducted
 * @property \Illuminate\Support\Carbon|null $last_deducted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_name',
        'sku',
        'daily_budget',
        'start_date',
        'end_date',
        'status',
        'total_deducted',
        'last_deducted_at',
    ];

    protected $casts = [
        'daily_budget' => 'decimal:2',
        'total_deducted' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'last_deducted_at' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'sku', 'sku');
    }

    /**
     * Get seller's available balance for ads:
     * Total Earnings - Ad Spend - Completed Payouts - Locked (Pending/Approved) Payouts
     */
    public static function getSellerBalance(int $sellerId): float
    {
        $totalEarnings = \App\Models\Order::where('seller_id', $sellerId)
            ->where('status', 'Delivered')
            ->sum('seller_earnings');

        // Get all campaign deductions for this seller
        $totalDeducted = self::whereHas('product', function ($q) use ($sellerId) {
            $q->where('seller_id', $sellerId);
        })->sum('total_deducted');

        // Subtract completed payouts (money already sent to seller)
        $completedPayouts = \App\Models\Payout::where('seller_id', $sellerId)
            ->where('status', 'completed')
            ->sum('net_amount');

        // Lock pending & approved payouts (money reserved for upcoming transfer)
        $lockedPayouts = \App\Models\Payout::where('seller_id', $sellerId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('net_amount');

        return max(0, (float) $totalEarnings - (float) $totalDeducted - (float) $completedPayouts - (float) $lockedPayouts);
    }
}
