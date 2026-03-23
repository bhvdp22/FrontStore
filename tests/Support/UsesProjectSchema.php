<?php

namespace Tests\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait UsesProjectSchema
{
    protected function setUpProjectSchema(): void
    {
        $databasePath = database_path('testing.sqlite');

        if (! file_exists($databasePath)) {
            touch($databasePath);
        }

        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', $databasePath);
        config()->set('mail.default', 'array');
        config()->set('session.driver', 'array');
        config()->set('cache.default', 'array');

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        $this->dropProjectTables();
        $this->createProjectTables();
        $this->seedProjectTables();
    }

    protected function dropProjectTables(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ([
            'returns',
            'payments',
            'order_items',
            'orders',
            'products',
            'business_settings',
            'customers',
            'admins',
            'user',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }

    protected function createProjectTables(): void
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('business_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('admin_commission', 5, 2)->default(10.00);
            $table->decimal('platform_fee', 8, 2)->default(20.00);
            $table->decimal('gst_percentage', 5, 2)->default(18.00);
            $table->boolean('tax_included_in_price')->default(false);
            $table->boolean('show_tax_on_invoice')->default(true);
            $table->boolean('show_platform_fee')->default(true);
            $table->string('platform_fee_label')->default('Platform Fee');
            $table->string('tax_label')->default('GST');
            $table->string('business_name')->nullable();
            $table->string('business_gstin')->nullable();
            $table->text('business_address')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('sku', 100)->unique();
            $table->string('asin')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->string('status')->default('active');
            $table->longText('description')->nullable();
            $table->string('img_path')->nullable();
            $table->boolean('is_sponsored')->default(false);
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 191)->unique();
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('product_name');
            $table->string('sku');
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('platform_fee', 10, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->decimal('seller_earnings', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->string('status')->default('Unshipped');
            $table->string('img_path')->nullable();
            $table->string('order_source')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_name');
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_id', 191)->unique();
            $table->string('order_id', 191);
            $table->decimal('amount', 10, 2);
            $table->string('payment_method', 100)->default('Cash on Delivery');
            $table->string('status', 50)->default('Pending');
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_order_id')->nullable();
            $table->text('razorpay_signature')->nullable();
            $table->timestamps();
        });

        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->string('return_number', 50)->unique();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('refund_amount', 10, 2);
            $table->string('return_reason');
            $table->text('reason_details')->nullable();
            $table->json('images')->nullable();
            $table->string('status')->default('pending');
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

    protected function seedProjectTables(): void
    {
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
}
