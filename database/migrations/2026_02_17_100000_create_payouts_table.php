<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->string('payout_id')->unique(); // e.g. PAY-20260217-00001
            $table->unsignedBigInteger('seller_id');
            $table->decimal('amount', 12, 2);
            $table->decimal('ad_deductions', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2); // amount - ad_deductions
            $table->string('status')->default('pending'); // pending, approved, rejected, completed
            $table->date('period_start'); // 7-day period start
            $table->date('period_end');   // 7-day period end
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('transaction_reference')->nullable(); // bank txn ref after payout
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable(); // admin id
            $table->timestamps();

            $table->foreign('seller_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
