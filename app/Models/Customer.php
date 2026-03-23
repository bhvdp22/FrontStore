<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    
    protected $fillable = [
        'name', 
        'email', 
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get customer's orders
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_email', 'email');
    }

    /**
     * Get customer addresses
     */
    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
