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
            if (!Schema::hasColumn('user', 'phone')) {
                $table->string('phone', 20)->unique()->nullable();
            }
            if (!Schema::hasColumn('user', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('user', 'country_code')) {
                $table->string('country_code', 10)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['phone', 'email', 'country_code']);
        });
    }
};
