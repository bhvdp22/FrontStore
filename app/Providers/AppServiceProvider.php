<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        // Share cart count with all views
        \View::composer('*', function ($view) {
            $cart = session('cart', []);
            $cartCount = 0;
            foreach ($cart as $item) {
                $cartCount += isset($item['quantity']) ? $item['quantity'] : 1;
            }
            $view->with('cartCount', $cartCount);
        });
    }
}
