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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 191)->unique();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('product_name');
            $table->string('sku');
            $table->integer('quantity');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('Unshipped');
            $table->string('img_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
