<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Allow these columns to be filled by your controller
    protected $fillable = [
        'payment_id',
        'order_id',
        'amount',
        'payment_method',
        'status',
        'razorpay_payment_id',
        'razorpay_order_id',
        'razorpay_signature',
    ];

    // Relationship to get the first order with this order_id
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}