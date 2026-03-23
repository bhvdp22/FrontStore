<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id',
        'sender_type',
        'sender_id',
        'message',
        'attachments',
        'is_read',
    ];

    protected $casts = [
        'attachments' => 'array',
        'is_read' => 'boolean',
    ];

    // Relationships
    public function productReturn()
    {
        return $this->belongsTo(ProductReturn::class, 'return_id');
    }

    // Get sender name
    public function getSenderNameAttribute()
    {
        if ($this->sender_type === 'customer') {
            $customer = \App\Models\Customer::find($this->sender_id);
            return $customer ? $customer->name : 'Customer';
        } elseif ($this->sender_type === 'seller') {
            $seller = \App\Models\User::find($this->sender_id);
            return $seller ? ($seller->business_name ?? $seller->name) : 'Seller';
        }
        return 'System';
    }

    // Scope for unread messages
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
