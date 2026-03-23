<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $country_code
 * @property string $password
 * @property string|null $business_name
 * @property string|null $status
 * @property string|null $business_address
 * @property string|null $city
 * @property string|null $state
 * @property string|null $pincode
 * @property string|null $country
 * @property string|null $gstin
 * @property string|null $pan
 * @property string|null $cin
 * @property string|null $bank_name
 * @property string|null $bank_account
 * @property string|null $ifsc_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = "user";
    protected $primaryKey = "id";

    protected $fillable = [
        'name', 
        'phone', 
        'email', 
        'country_code', 
        'password',
        // Business Details
        'business_name',
        'status',
        'business_address',
        'city',
        'state',
        'pincode',
        'country',
        // Tax & Legal
        'gstin',
        'pan',
        'cin',
        // Bank Details
        'bank_name',
        'bank_account',
        'ifsc_code',
        // Storefront
        'slug',
        'brand_story',
        'banner_image',
        'logo',
        'social_links',
        'storefront_enabled',
        // Reputation
        'seller_reputation_score',
        'seller_reputation_badge',
        'seller_reputation_breakdown',
        'seller_reputation_calculated_at',
    ];

    protected $casts = [
        'seller_reputation_score' => 'integer',
        'seller_reputation_breakdown' => 'array',
        'seller_reputation_calculated_at' => 'datetime',
        'social_links' => 'array',
        'storefront_enabled' => 'boolean',
    ];

    /**
     * Get full business address for invoices
     */
    public function getFullBusinessAddress(): string
    {
        $parts = array_filter([
            $this->business_address,
            $this->city,
            $this->state,
            $this->pincode,
            $this->country,
        ]);
        return implode(', ', $parts);
    }

    /**
     * Get products belonging to this seller
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }
}

