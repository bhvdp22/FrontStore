<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_commission',
        'platform_fee',
        'gst_percentage',
        'tax_included_in_price',
        'show_tax_on_invoice',
        'show_platform_fee',
        'platform_fee_label',
        'tax_label',
        'business_name',
        'business_gstin',
        'business_address',
    ];

    protected $casts = [
        'admin_commission' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'gst_percentage' => 'decimal:2',
        'tax_included_in_price' => 'boolean',
        'show_tax_on_invoice' => 'boolean',
        'show_platform_fee' => 'boolean',
    ];

    /**
     * Get the current business settings (singleton pattern)
     */
    public static function current()
    {
        return static::first() ?? static::create([
            'admin_commission' => 10.00,
            'platform_fee' => 20.00,
            'gst_percentage' => 18.00,
        ]);
    }

    /**
     * Get a specific setting value
     */
    public static function get($key, $default = null)
    {
        $settings = static::current();
        return $settings->$key ?? $default;
    }
}
