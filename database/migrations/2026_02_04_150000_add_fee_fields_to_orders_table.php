<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration adds fee tracking fields to orders:
     * - subtotal: Base product price before any fees
     * - tax_amount: GST/Tax applied to the order
     * - platform_fee: Fixed platform fee charged to customer
     * - admin_commission: Commission percentage taken from seller
     * - seller_earnings: Amount seller receives after commission
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('subtotal', 10, 2)->default(0)->after('total_price');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('subtotal');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('tax_rate');
            $table->decimal('platform_fee', 10, 2)->default(0)->after('tax_amount');
            $table->decimal('commission_rate', 5, 2)->default(0)->after('platform_fee');
            $table->decimal('commission_amount', 10, 2)->default(0)->after('commission_rate');
            $table->decimal('seller_earnings', 10, 2)->default(0)->after('commission_amount');
            $table->unsignedBigInteger('seller_id')->nullable()->after('seller_earnings');
            
            $table->index('seller_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'subtotal',
                'tax_rate',
                'tax_amount',
                'platform_fee',
                'commission_rate',
                'commission_amount',
                'seller_earnings',
                'seller_id',
            ]);
        });
    }
};
