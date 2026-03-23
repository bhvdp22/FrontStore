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
            $table->string('slug', 100)->nullable()->unique()->after('business_name');
            $table->text('brand_story')->nullable()->after('slug');
            $table->string('banner_image', 500)->nullable()->after('brand_story');
            $table->string('logo', 500)->nullable()->after('banner_image');
            $table->json('social_links')->nullable()->after('logo');
            $table->boolean('storefront_enabled')->default(false)->after('social_links');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'brand_story',
                'banner_image',
                'logo',
                'social_links',
                'storefront_enabled',
            ]);
        });
    }
};
