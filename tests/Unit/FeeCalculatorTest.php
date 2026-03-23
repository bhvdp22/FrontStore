<?php

namespace Tests\Unit;

use App\Models\BusinessSetting;
use App\Services\FeeCalculator;
use Tests\TestCase;

class FeeCalculatorTest extends TestCase
{
    public function test_it_calculates_total_amount_and_seller_earnings_correctly(): void
    {
        BusinessSetting::query()->update([
            'admin_commission' => 10.00,
            'platform_fee' => 20.00,
            'gst_percentage' => 18.00,
            'tax_included_in_price' => false,
            'show_tax_on_invoice' => true,
            'show_platform_fee' => true,
            'platform_fee_label' => 'Platform Fee',
            'tax_label' => 'GST',
        ]);

        $fees = app(FeeCalculator::class)->calculate(1000);

        $this->assertEquals(1000.00, $fees['subtotal']);
        $this->assertEquals(18.00, $fees['tax_rate']);
        $this->assertEquals(180.00, $fees['tax_amount']);
        $this->assertEquals(20.00, $fees['platform_fee']);
        $this->assertEquals(10.00, $fees['commission_rate']);
        $this->assertEquals(100.00, $fees['commission_amount']);
        $this->assertEquals(900.00, $fees['seller_earnings']);
        $this->assertEquals(1200.00, $fees['total']);
    }
}
