<?php

namespace App\Services;

use App\Models\BusinessSetting;

/**
 * FeeCalculator Service
 * 
 * Handles all commission, platform fee, and tax calculations.
 * 
 * HOW FEES WORK:
 * ===============
 * 
 * 1. SUBTOTAL: Base price of products (price × quantity)
 * 
 * 2. GST/TAX (Charged to Customer):
 *    - Applied on subtotal
 *    - Example: 18% GST on ₹1000 = ₹180
 *    - If "tax_included_in_price" is true, tax is extracted from price
 * 
 * 3. PLATFORM FEE (Charged to Customer):
 *    - Fixed fee per order (e.g., ₹20)
 *    - Covers platform operational costs
 *    - Goes 100% to admin
 * 
 * 4. TOTAL = Subtotal + Tax + Platform Fee
 * 
 * 5. ADMIN COMMISSION (Deducted from Seller):
 *    - Percentage of subtotal taken from seller
 *    - Example: 10% on ₹1000 = ₹100
 *    - Goes to admin
 * 
 * 6. SELLER EARNINGS = Subtotal - Commission
 *    - What seller actually receives
 * 
 * MONEY FLOW:
 * ===========
 * Customer pays: ₹1000 (product) + ₹180 (GST) + ₹20 (platform fee) = ₹1200
 * Admin receives: ₹100 (commission) + ₹20 (platform fee) + ₹180 (tax) = ₹300
 * Seller receives: ₹1000 - ₹100 = ₹900
 */
class FeeCalculator
{
    protected $settings;
    
    public function __construct()
    {
        $this->settings = BusinessSetting::current();
    }

    /**
     * Calculate all fees for an order
     * 
     * @param float $subtotal The base product total (price × quantity)
     * @return array All calculated fees
     */
    public function calculate(float $subtotal): array
    {
        $taxRate = (float) $this->settings->gst_percentage;
        $commissionRate = (float) $this->settings->admin_commission;
        $platformFee = (float) $this->settings->platform_fee;
        $taxIncluded = $this->settings->tax_included_in_price;

        // Calculate tax
        if ($taxIncluded) {
            // Tax is included in price, extract it
            // Price = Base + Tax, so Base = Price / (1 + rate/100)
            $baseAmount = $subtotal / (1 + ($taxRate / 100));
            $taxAmount = $subtotal - $baseAmount;
            $subtotal = $baseAmount; // Adjust subtotal to be pre-tax amount
        } else {
            // Tax is additional
            $taxAmount = $subtotal * ($taxRate / 100);
        }

        // Calculate total for customer
        $total = $subtotal + $taxAmount + $platformFee;

        // Calculate commission (from seller's portion)
        $commissionAmount = $subtotal * ($commissionRate / 100);
        
        // Seller earnings = subtotal minus commission
        $sellerEarnings = $subtotal - $commissionAmount;

        return [
            'subtotal' => round($subtotal, 2),
            'tax_rate' => $taxRate,
            'tax_amount' => round($taxAmount, 2),
            'platform_fee' => round($platformFee, 2),
            'commission_rate' => $commissionRate,
            'commission_amount' => round($commissionAmount, 2),
            'seller_earnings' => round($sellerEarnings, 2),
            'total' => round($total, 2),
            
            // Additional info for display
            'tax_label' => $this->settings->tax_label ?? 'GST',
            'platform_fee_label' => $this->settings->platform_fee_label ?? 'Platform Fee',
            'show_platform_fee' => $this->settings->show_platform_fee,
            'show_tax' => $this->settings->show_tax_on_invoice,
        ];
    }

    /**
     * Calculate fees for cart items
     * 
     * @param array $cartItems Cart items with price and quantity
     * @return array Fees breakdown
     */
    public function calculateForCart(array $cartItems): array
    {
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        
        return $this->calculate($subtotal);
    }

    /**
     * Get breakdown for display on frontend
     */
    public function getDisplayBreakdown(float $subtotal): array
    {
        $fees = $this->calculate($subtotal);
        
        $breakdown = [
            [
                'label' => 'Subtotal',
                'amount' => $fees['subtotal'],
                'show' => true,
            ],
        ];

        if ($fees['show_tax'] && $fees['tax_amount'] > 0) {
            $breakdown[] = [
                'label' => $fees['tax_label'] . ' (' . $fees['tax_rate'] . '%)',
                'amount' => $fees['tax_amount'],
                'show' => true,
            ];
        }

        if ($fees['show_platform_fee'] && $fees['platform_fee'] > 0) {
            $breakdown[] = [
                'label' => $fees['platform_fee_label'],
                'amount' => $fees['platform_fee'],
                'show' => true,
            ];
        }

        $breakdown[] = [
            'label' => 'Total',
            'amount' => $fees['total'],
            'show' => true,
            'isTotal' => true,
        ];

        return $breakdown;
    }

    /**
     * Get settings for display
     */
    public function getSettings(): BusinessSetting
    {
        return $this->settings;
    }

    /**
     * Calculate earnings breakdown for seller dashboard
     */
    public function getSellerBreakdown(float $orderSubtotal): array
    {
        $fees = $this->calculate($orderSubtotal);
        
        return [
            'order_value' => $fees['subtotal'],
            'commission' => $fees['commission_amount'],
            'commission_rate' => $fees['commission_rate'],
            'your_earnings' => $fees['seller_earnings'],
        ];
    }
}
