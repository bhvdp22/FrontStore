<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type');              // order, payout, ad, stock, system
            $table->string('title');
            $table->text('message');
            $table->string('icon')->default('info'); // info, success, warning, danger
            $table->string('action_url')->nullable();
            
            // Polymorphic: who receives this notification
            $table->string('recipient_type');     // 'seller' or 'admin'
            $table->unsignedBigInteger('recipient_id'); // user.id or admin.id
            
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['recipient_type', 'recipient_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
