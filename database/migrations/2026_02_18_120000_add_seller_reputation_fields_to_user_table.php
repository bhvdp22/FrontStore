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
            $table->unsignedTinyInteger('seller_reputation_score')->nullable()->after('status');
            $table->string('seller_reputation_badge', 50)->nullable()->after('seller_reputation_score');
            $table->json('seller_reputation_breakdown')->nullable()->after('seller_reputation_badge');
            $table->timestamp('seller_reputation_calculated_at')->nullable()->after('seller_reputation_breakdown');

            $table->index('seller_reputation_score');
            $table->index('seller_reputation_badge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropIndex(['seller_reputation_score']);
            $table->dropIndex(['seller_reputation_badge']);
            $table->dropColumn([
                'seller_reputation_score',
                'seller_reputation_badge',
                'seller_reputation_breakdown',
                'seller_reputation_calculated_at',
            ]);
        });
    }
};
