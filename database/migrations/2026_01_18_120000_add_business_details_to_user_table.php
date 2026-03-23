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
        Schema::table('user', function (Blueprint $table) {
            // Business Details
            $table->string('business_name')->nullable()->after('email');
            $table->text('business_address')->nullable()->after('business_name');
            $table->string('city')->nullable()->after('business_address');
            $table->string('state')->nullable()->after('city');
            $table->string('pincode', 10)->nullable()->after('state');
            $table->string('country')->default('India')->after('pincode');
            
            // Tax & Legal Details
            $table->string('gstin', 20)->nullable()->after('country');
            $table->string('pan', 15)->nullable()->after('gstin');
            $table->string('cin', 25)->nullable()->after('pan');
            
            // Optional: Bank Details for payments
            $table->string('bank_name')->nullable()->after('cin');
            $table->string('bank_account')->nullable()->after('bank_name');
            $table->string('ifsc_code', 15)->nullable()->after('bank_account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn([
                'business_name',
                'business_address',
                'city',
                'state',
                'pincode',
                'country',
                'gstin',
                'pan',
                'cin',
                'bank_name',
                'bank_account',
                'ifsc_code',
            ]);
        });
    }
};
