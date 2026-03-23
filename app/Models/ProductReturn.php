<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductReturn extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'return_number',
        'order_id',
        'order_item_id',
        'customer_id',
        'seller_id',
        'product_id',
        'quantity',
        'refund_amount',
        'return_reason',
        'reason_details',
        'images',
        'status',
        'seller_notes',
        'admin_notes',
        'approved_at',
        'rejected_at',
        'pickup_scheduled_at',
        'picked_up_at',
        'received_at',
        'inspected_at',
        'refund_initiated_at',
        'refund_completed_at',
        'pickup_address',
        'tracking_number',
        'courier_name',
    ];

    protected $casts = [
        'images' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'pickup_scheduled_at' => 'datetime',
        'picked_up_at' => 'datetime',
        'received_at' => 'datetime',
        'inspected_at' => 'datetime',
        'refund_initiated_at' => 'datetime',
        'refund_completed_at' => 'datetime',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function refund()
    {
        return $this->hasOne(Refund::class, 'return_id');
    }

    public function messages()
    {
        return $this->hasMany(ReturnMessage::class, 'return_id');
    }

    // Generate unique return number
    public static function generateReturnNumber()
    {
        $prefix = 'RET';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -5));
        return "{$prefix}{$date}{$random}";
    }

    // Generate unique tracking number
    public static function generateTrackingNumber()
    {
        $prefix = 'TRK';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        return "{$prefix}{$date}{$random}";
    }

    // Get return reason label
    public function getReasonLabelAttribute()
    {
        $labels = [
            'defective' => 'Product is defective',
            'wrong_item' => 'Wrong item received',
            'not_as_described' => 'Product not as described',
            'damaged_in_shipping' => 'Damaged during shipping',
            'size_fit_issue' => 'Size/Fit issue',
            'changed_mind' => 'Changed my mind',
            'quality_issue' => 'Quality not satisfactory',
            'late_delivery' => 'Late delivery',
            'other' => 'Other reason',
        ];
        return $labels[$this->return_reason] ?? $this->return_reason;
    }

    // Get status label
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'pickup_scheduled' => 'Pickup Scheduled',
            'picked_up' => 'Picked Up',
            'received' => 'Received by Seller',
            'inspected' => 'Inspected',
            'refund_initiated' => 'Refund Initiated',
            'refund_completed' => 'Refund Completed',
            'closed' => 'Closed',
            'cancelled' => 'Cancelled',
        ];
        return $labels[$this->status] ?? $this->status;
    }

    // Get status color for UI
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => '#f59e0b',
            'approved' => '#10b981',
            'rejected' => '#ef4444',
            'pickup_scheduled' => '#3b82f6',
            'picked_up' => '#8b5cf6',
            'received' => '#6366f1',
            'inspected' => '#14b8a6',
            'refund_initiated' => '#f97316',
            'refund_completed' => '#22c55e',
            'closed' => '#6b7280',
            'cancelled' => '#dc2626',
        ];
        return $colors[$this->status] ?? '#6b7280';
    }

    // Check if return can be cancelled
    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    // Check if return is eligible for refund
    public function isEligibleForRefund()
    {
        return in_array($this->status, ['received', 'inspected']);
    }

    // Scope for pending returns
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope for seller
    public function scopeForSeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    // Scope for customer
    public function scopeForCustomer($query, $customerId)
    {
        return $query->where('customer_id', $customerId);
    }
}
