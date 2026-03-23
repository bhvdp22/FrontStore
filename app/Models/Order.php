<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'order_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'customer_id',
        'shipping_address',
        'product_name',
        'sku',
        'quantity',
        'total_price',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'platform_fee',
        'commission_rate',
        'commission_amount',
        'seller_earnings',
        'seller_id',
        'grand_total',
        'status',
        'img_path',
        'order_source',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'seller_earnings' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    // Add this function inside the class
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Also connect to User so we know WHO bought it
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Alias for customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Relationship to seller
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Relationship to payment
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    // Relationship to product
    public function product()
    {
        return $this->belongsTo(Product::class, 'sku', 'sku');
    }

    /**
     * Get formatted total with currency
     */
    public function getFormattedTotalAttribute(): string
    {
        return '₹' . number_format((float) $this->total_price, 2);
    }

    /**
     * Get formatted seller earnings
     */
    public function getFormattedSellerEarningsAttribute(): string
    {
        return '₹' . number_format((float) $this->seller_earnings, 2);
    }

    /**
     * Get fee breakdown for invoice
     */
    public function getFeeBreakdown(): array
    {
        return [
            'subtotal' => (float) $this->subtotal,
            'tax_rate' => (float) $this->tax_rate,
            'tax_amount' => (float) $this->tax_amount,
            'platform_fee' => (float) $this->platform_fee,
            'total' => (float) $this->total_price,
        ];
    }

    /**
     * Get seller breakdown
     */
    public function getSellerBreakdown(): array
    {
        return [
            'order_value' => (float) $this->subtotal,
            'commission_rate' => (float) $this->commission_rate,
            'commission_amount' => (float) $this->commission_amount,
            'earnings' => (float) $this->seller_earnings,
        ];
    }
}
