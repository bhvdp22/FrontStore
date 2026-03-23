<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Refund extends Model
{
    use HasFactory;

    protected $fillable = [
        'refund_number',
        'return_id',
        'order_id',
        'customer_id',
        'payment_id',
        'amount',
        'refund_method',
        'status',
        'razorpay_refund_id',
        'transaction_id',
        'failure_reason',
        'notes',
        'initiated_at',
        'completed_at',
        'failed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'initiated_at' => 'datetime',
        'completed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    // Relationships
    public function productReturn()
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    // Generate unique refund number
    public static function generateRefundNumber()
    {
        $prefix = 'REF';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        return "{$prefix}{$date}{$random}";
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'completed' => 'Completed',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    // Get status color
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => '#f59e0b',
            'processing' => '#3b82f6',
            'completed' => '#22c55e',
            'failed' => '#ef4444',
            'cancelled' => '#6b7280',
        ];
        return $colors[$this->status] ?? '#6b7280';
    }

    // Get refund method label
    public function getRefundMethodLabelAttribute()
    {
        $labels = [
            'original_payment' => 'Original Payment Method',
            'bank_transfer' => 'Bank Transfer',
            'store_credit' => 'Store Credit',
            'upi' => 'UPI',
        ];
        return $labels[$this->refund_method] ?? $this->refund_method;
    }

    // Scope for pending refunds
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for completed refunds
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
