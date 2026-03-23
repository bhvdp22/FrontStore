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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number', 50)->unique();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('order_item_id')->constrained('order_items')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('seller_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('refund_amount', 10, 2);
            $table->enum('return_reason', [
                'defective',
                'wrong_item',
                'not_as_described',
                'damaged_in_shipping',
                'size_fit_issue',
                'changed_mind',
                'quality_issue',
                'late_delivery',
                'other'
            ]);
            $table->text('reason_details')->nullable();
            $table->json('images')->nullable(); // Customer uploaded images
            $table->enum('status', [
                'pending',           // Customer submitted return request
                'approved',          // Seller approved return
                'rejected',          // Seller rejected return
                'pickup_scheduled',  // Pickup scheduled for return
                'picked_up',         // Item picked up from customer
                'received',          // Seller received the item
                'inspected',         // Item inspected by seller
                'refund_initiated',  // Refund process started
                'refund_completed',  // Refund completed
                'closed',            // Return closed
                'cancelled'          // Return cancelled by customer
            ])->default('pending');
            $table->text('seller_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('pickup_scheduled_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamp('inspected_at')->nullable();
            $table->timestamp('refund_initiated_at')->nullable();
            $table->timestamp('refund_completed_at')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('courier_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
