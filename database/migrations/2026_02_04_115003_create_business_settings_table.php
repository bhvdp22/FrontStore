<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            
            // Commission & Fees
            $table->decimal('admin_commission', 5, 2)->default(10.00); // % taken from seller
            $table->decimal('platform_fee', 8, 2)->default(20.00);     // Fixed fee per order (₹)
            $table->decimal('gst_percentage', 5, 2)->default(18.00);   // GST rate
            
            // Tax settings
            $table->boolean('tax_included_in_price')->default(false); // Is tax included in product price?
            $table->boolean('show_tax_on_invoice')->default(true);    // Show tax breakdown on invoice
            
            // Fee display settings
            $table->boolean('show_platform_fee')->default(true);      // Show platform fee to customer
            $table->string('platform_fee_label')->default('Platform Fee'); // Label for platform fee
            $table->string('tax_label')->default('GST');              // Tax label (GST, VAT, etc.)
            
            // Business info for invoices
            $table->string('business_name')->nullable();
            $table->string('business_gstin')->nullable();
            $table->text('business_address')->nullable();
            
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('business_settings')->insert([
            'admin_commission' => 10.00,
            'platform_fee' => 20.00,
            'gst_percentage' => 18.00,
            'tax_included_in_price' => false,
            'show_tax_on_invoice' => true,
            'show_platform_fee' => true,
            'platform_fee_label' => 'Platform Fee',
            'tax_label' => 'GST',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_settings');
    }
};
