<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AuthController; 
use App\Http\Middleware\PreventBackHistory;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerAddressController;
use App\Http\Middleware\RequireLogin;
use App\Http\Middleware\RequireCustomerLogin;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SellerProfileController;
use App\Http\Controllers\SellerStorefrontController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Facades\Route as FacadesRoute;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminSettingsController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminSellerController;
use App\Http\Controllers\AdminCustomerController;
use App\Http\Controllers\AdminReturnController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminReviewController;
Route::get('/run-migrations', function () {
    $log = [];

    // Surgical Patch: The manual SQL dump appears severely fragmented. 
    // If seller_id is missing, we explicitly force-add all critical fee fields, ignoring standard migrations.
    try {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'seller_id')) {
            \Illuminate\Support\Facades\Schema::table('orders', function ($table) {
                // Ignore errors inside this closure if a singular column already exists
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'seller_id')) $table->unsignedBigInteger('seller_id')->nullable();
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'subtotal')) $table->decimal('subtotal', 10, 2)->default(0);
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'tax_rate')) $table->decimal('tax_rate', 5, 2)->default(0);
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'tax_amount')) $table->decimal('tax_amount', 10, 2)->default(0);
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'platform_fee')) $table->decimal('platform_fee', 10, 2)->default(0);
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'commission_rate')) $table->decimal('commission_rate', 5, 2)->default(0);
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'commission_amount')) $table->decimal('commission_amount', 10, 2)->default(0);
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'seller_earnings')) $table->decimal('seller_earnings', 10, 2)->default(0);
                if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'grand_total')) $table->decimal('grand_total', 10, 2)->default(0);
            });
            $log[] = "Surgically patched orders table columns.";
        }
    } catch (\Exception $e) {
        $log[] = "Surgical patch error: " . $e->getMessage();
    }

    $files = glob(database_path('migrations/*.php'));
    sort($files);
    
    foreach ($files as $file) {
        $migrationName = str_replace('.php', '', basename($file));
        
        if (\Illuminate\Support\Facades\DB::table('migrations')->where('migration', $migrationName)->exists()) {
            continue;
        }

        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', [
                '--path' => 'database/migrations/' . basename($file),
                '--force' => true
            ]);
            $log[] = "Successfully migrated: $migrationName";
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::table('migrations')->insertOrIgnore([
                'migration' => $migrationName,
                'batch' => (\Illuminate\Support\Facades\DB::table('migrations')->max('batch') ?? 0) + 1
            ]);
            $log[] = "Skipped (Already exists): $migrationName";
        }
    }
    
    return response()->json(['message' => 'Self-healing & Surgical Patch complete!', 'log' => $log]);
});

Route::get('/setup-admin', function () {
    \Illuminate\Support\Facades\Artisan::call('db:seed', [
        '--class' => 'AdminSeeder', 
        '--force' => true
    ]);
    return 'Admin user successfully created! You can now log in with admin@example.com and admin123';
});

Route::get('/setsession', function(){
    session(['loginusername' => 'Bhavdeep Mangukiya']);
    echo "Session set successfully.";
});

Route::get('/getsession', function(){
    echo session('loginusername');
});

Route::get('/', [DashboardController::class, 'index']);

Route::middleware([PreventBackHistory::class])->group(function () {
    
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    
    Route::get('/reports', [ReportController::class, 'index'])->name('report');
    
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{id}/pay', [PaymentController::class, 'updateStatus'])->name('payments.update');
    Route::post('/payment/create', [PaymentController::class, 'createRazorpayOrder'])->name('payment.create');
    Route::post('/payment/verify', [PaymentController::class, 'verifyPayment'])->name('payment.verify');

    // Add other protected routes here...
});

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Seller Profile Routes
Route::get('/seller/profile', [SellerProfileController::class, 'show'])->name('seller.profile');
Route::get('/seller/profile/edit', [SellerProfileController::class, 'edit'])->name('seller.profile.edit');
Route::post('/seller/profile/update', [SellerProfileController::class, 'update'])->name('seller.profile.update');

Route::get('/about',[UserController::class, 'about']);

Route::get('/users', [UserController::class, 'listUsers']);
Route::get('/users/edit{id}', [UserController::class, 'edit']);
Route::post('/users/update{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/delete/{id}', [UserController::class, 'delete']);

Route::get('/users/create',[UserController::class, 'create'])->name('users.create');
Route::post('/users/store',[UserController::class, 'store'])->name('users.store');

Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::post('/products/{id}/quick-stock', [ProductController::class, 'quickUpdateStock'])->name('products.quickUpdateStock');

Route::get('/products/{id}/image', [ProductController::class, 'manageImage'])->name('products.manageImage');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/help', [HelpController::class, 'index'])->name('help');

// Orders
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/orders/{id}/packing-slip', [OrderController::class, 'printPackingSlip'])->name('orders.packingSlip');
Route::get('/orders/{id}/edit', [OrderController::class, 'edit'])->name('orders.edit');
Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

// Invoice
Route::get('/orders/{id}/invoice', [App\Http\Controllers\InvoiceController::class, 'generateInvoice'])->name('orders.invoice');
Route::get('/orders/{id}/invoice/preview', [App\Http\Controllers\InvoiceController::class, 'previewInvoice'])->name('orders.invoice.preview');

//Ads
Route::get('/advertising', [AdController::class, 'index'])->name('ads.index');
Route::get('/advertising/create', [AdController::class, 'create'])->name('ads.create');
Route::post('/advertising', [AdController::class, 'store'])->name('ads.store');
Route::delete('/advertising/{id}', [AdController::class, 'destroy'])->name('ads.destroy');
Route::post('/advertising/{id}/toggle', [App\Http\Controllers\AdController::class, 'toggleStatus'])->name('ads.toggle');

// Seller Notifications (JSON API for bell icon)
Route::get('/notifications', [NotificationController::class, 'sellerNotifications'])->name('notifications.seller');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllReadSeller'])->name('notifications.markAllReadSeller');

// Seller Withdraw (manual payout request)
Route::post('/payments/withdraw', [NotificationController::class, 'sellerWithdraw'])->name('payments.withdraw');


// shop (requires user to be logged in)
// Customer login/register
// Shortcut: Visit My Shop (prompts customer login if needed)
Route::get('/visit-my-shop', function () {
    // Always set intended to shop index
    session(['intended_url' => route('shop.index')]);
    if (!session()->has('customer_email')) {
        return redirect()->route('customer.login');
    }
    return redirect()->route('shop.index');
})->name('visit.shop');
Route::get('/customer/register', [CustomerAuthController::class, 'showRegister'])->name('customer.register');
Route::post('/customer/register', [CustomerAuthController::class, 'register'])->name('customer.register.submit');
Route::get('/customer/login', [CustomerAuthController::class, 'showLogin'])->name('customer.login');
Route::post('/customer/login', [CustomerAuthController::class, 'login'])->name('customer.login.submit');
Route::get('/customer/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');

// Shop for customers (requires customer login)
Route::middleware([RequireCustomerLogin::class])->group(function () {
    Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/shop/product/{id}', [ShopController::class, 'product'])->name('shop.product');
    Route::get('/shop/cart', [ShopController::class, 'cart'])->name('shop.cart');
    Route::post('/shop/add-to-cart', [ShopController::class, 'addToCart'])->name('shop.addToCart');
    Route::post('/shop/update-cart', [ShopController::class, 'updateCart'])->name('shop.updateCart');
    Route::post('/shop/remove-from-cart', [ShopController::class, 'removeFromCart'])->name('shop.removeFromCart');
    Route::get('/shop/checkout', [ShopController::class, 'checkout'])->middleware('allow.razorpay.csp')->name('shop.checkout');
    Route::post('/shop/place-order', [ShopController::class, 'placeOrder'])->name('shop.placeOrder');
    Route::get('/shop/success', [ShopController::class, 'success'])->name('shop.success');
    Route::get('/shop/help', [ShopController::class, 'help'])->name('shop.help');
});

//cart (old routes - keep for backward compatibility)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::get('/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('/cart/increase/{id}', [App\Http\Controllers\CartController::class, 'increaseQty'])->name('cart.increase');
Route::get('/cart/decrease/{id}', [App\Http\Controllers\CartController::class, 'decreaseQty'])->name('cart.decrease');

//reviews
Route::group(['middleware' => ['web']], function () {
    // Customer review form and submission (require customer login)
    Route::middleware([RequireCustomerLogin::class])->group(function () {
        Route::get('/products/{product}/review', [ReviewController::class, 'showForm'])->name('review.form');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    });
    
    // Get product reviews (for displaying on product page)
    Route::get('/products/{product}/reviews', [ReviewController::class, 'getProductReviews'])->name('product.reviews');
    Route::get('/products/{product}/review-stats', [ReviewController::class, 'getProductReviewStats'])->name('product.review-stats');
    
    // Mark review as helpful
    Route::post('/reviews/{id}/helpful', [ReviewController::class, 'markHelpful']);
    
    // Admin review management (without auth middleware for now)
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{id}/approve', [ReviewController::class, 'approve']);
    Route::post('/reviews/{id}/reject', [ReviewController::class, 'reject']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);
});

//profile & orders
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile/orders', [ProfileController::class, 'orders'])->name('profile.orders');
Route::get('/profile/track/{orderId}', [ProfileController::class, 'track'])->name('profile.track');
Route::get('/profile/reviews', [ProfileController::class, 'reviews'])->name('profile.reviews');
Route::get('/profile/invoice/{orderId}', [App\Http\Controllers\InvoiceController::class, 'customerInvoice'])->name('profile.invoice');

//customer addresses
Route::get('/profile/addresses', [CustomerAddressController::class, 'index'])->name('profile.addresses');
Route::post('/profile/addresses', [CustomerAddressController::class, 'store'])->name('profile.addresses.store');
Route::put('/profile/addresses/{id}', [CustomerAddressController::class, 'update'])->name('profile.addresses.update');
Route::delete('/profile/addresses/{id}', [CustomerAddressController::class, 'destroy'])->name('profile.addresses.destroy');
Route::post('/profile/addresses/{id}/default', [CustomerAddressController::class, 'setDefault'])->name('profile.addresses.setDefault');

// Returns & Refunds - Customer Routes
Route::middleware([RequireCustomerLogin::class])->group(function () {
    Route::get('/returns', [App\Http\Controllers\ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/eligible', [App\Http\Controllers\ReturnController::class, 'eligibleOrders'])->name('returns.eligible');
    Route::get('/returns/create', [App\Http\Controllers\ReturnController::class, 'create'])->name('returns.create');
    Route::post('/returns', [App\Http\Controllers\ReturnController::class, 'store'])->name('returns.store');
    Route::get('/returns/{id}', [App\Http\Controllers\ReturnController::class, 'show'])->name('returns.show');
    Route::post('/returns/{id}/cancel', [App\Http\Controllers\ReturnController::class, 'cancel'])->name('returns.cancel');
    Route::post('/returns/{id}/message', [App\Http\Controllers\ReturnController::class, 'sendMessage'])->name('returns.message');
    Route::get('/returns/{id}/track-refund', [App\Http\Controllers\ReturnController::class, 'trackRefund'])->name('returns.track-refund');
});

// Returns & Refunds - Seller Routes
Route::middleware([PreventBackHistory::class])->group(function () {
    Route::get('/seller/returns', [App\Http\Controllers\SellerReturnController::class, 'index'])->name('seller.returns.index');
    Route::get('/seller/returns/export', [App\Http\Controllers\SellerReturnController::class, 'export'])->name('seller.returns.export');
    Route::get('/seller/returns/{id}', [App\Http\Controllers\SellerReturnController::class, 'show'])->name('seller.returns.show');
    Route::post('/seller/returns/{id}/approve', [App\Http\Controllers\SellerReturnController::class, 'approve'])->name('seller.returns.approve');
    Route::post('/seller/returns/{id}/reject', [App\Http\Controllers\SellerReturnController::class, 'reject'])->name('seller.returns.reject');
    Route::post('/seller/returns/{id}/schedule-pickup', [App\Http\Controllers\SellerReturnController::class, 'schedulePickup'])->name('seller.returns.schedule-pickup');
    Route::post('/seller/returns/{id}/mark-picked-up', [App\Http\Controllers\SellerReturnController::class, 'markPickedUp'])->name('seller.returns.mark-picked-up');
    Route::post('/seller/returns/{id}/mark-received', [App\Http\Controllers\SellerReturnController::class, 'markReceived'])->name('seller.returns.mark-received');
    Route::post('/seller/returns/{id}/complete-inspection', [App\Http\Controllers\SellerReturnController::class, 'completeInspection'])->name('seller.returns.complete-inspection');
    Route::post('/seller/returns/{id}/initiate-refund', [App\Http\Controllers\SellerReturnController::class, 'initiateRefund'])->name('seller.returns.initiate-refund');
    Route::post('/seller/returns/{id}/complete-refund', [App\Http\Controllers\SellerReturnController::class, 'completeRefund'])->name('seller.returns.complete-refund');
    Route::post('/seller/returns/{id}/message', [App\Http\Controllers\SellerReturnController::class, 'sendMessage'])->name('seller.returns.message');
});

// Public Seller Storefront (after all /seller/* literal routes to avoid slug collision)
Route::get('/seller/{slug}', [SellerStorefrontController::class, 'show'])->name('seller.storefront');


// --- ADMIN ROUTES GROUP ---
Route::prefix('admin')->name('admin.')->group(function() {
    
    // 1. Login Routes (Public - No Login Required)
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminController::class, 'login'])->name('login.submit');

    // 2. Protected Routes (REQUIRE LOGIN)
    Route::middleware('auth:admin')->group(function() {
        
        // Dashboard & Logout
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard/chart-data', [AdminController::class, 'chartData'])->name('dashboard.chart-data');
        Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

        // Business Settings
        Route::get('/settings', [AdminSettingsController::class, 'index'])->name('settings');
        Route::post('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');

        // Category Management
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('categories');
        Route::post('/categories', [AdminCategoryController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('categories.delete');

        // Seller Management
        Route::get('/seller', [AdminSellerController::class, 'index'])->name('sellers');
        Route::get('/seller/{id}', [AdminSellerController::class, 'show'])->name('sellers.show');
        Route::post('/seller/{id}/approve', [AdminSellerController::class, 'approve'])->name('sellers.approve');
        Route::post('/seller/{id}/ban', [AdminSellerController::class, 'ban'])->name('sellers.ban');
        Route::post('/seller/{id}/pending', [AdminSellerController::class, 'pending'])->name('sellers.pending');

        // Order Management
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{id}', [AdminController::class, 'orderShow'])->name('orders.show');

        // Customer Management
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');
        Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('customers.show');

        // Product Overview
        Route::get('/products', [AdminProductController::class, 'index'])->name('products');
        Route::get('/products/{id}', [AdminProductController::class, 'show'])->name('products.show');

        // Returns Management
        Route::get('/returns', [AdminReturnController::class, 'index'])->name('returns');
        Route::get('/returns/{id}', [AdminReturnController::class, 'show'])->name('returns.show');
        Route::post('/returns/{id}/approve', [AdminReturnController::class, 'approve'])->name('returns.approve');
        Route::post('/returns/{id}/reject', [AdminReturnController::class, 'reject'])->name('returns.reject');
        Route::post('/returns/{id}/initiate-refund', [AdminReturnController::class, 'initiateRefund'])->name('returns.initiate-refund');
        Route::post('/returns/{id}/process-refund', [AdminReturnController::class, 'processRefund'])->name('returns.process-refund');

        // Refunds
        Route::get('/refunds', [AdminReturnController::class, 'refunds'])->name('refunds');

        // Payouts
        Route::get('/payouts', [AdminPayoutController::class, 'index'])->name('payouts');
        Route::get('/payouts/{id}', [AdminPayoutController::class, 'show'])->name('payouts.show');
        Route::post('/payouts/{id}/approve', [AdminPayoutController::class, 'approve'])->name('payouts.approve');
        Route::post('/payouts/{id}/reject', [AdminPayoutController::class, 'reject'])->name('payouts.reject');
        Route::post('/payouts/{id}/complete', [AdminPayoutController::class, 'complete'])->name('payouts.complete');

        // Review Moderation
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews');
        Route::post('/reviews/{id}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{id}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        // Admin Notifications (JSON API for bell icon)
        Route::get('/notifications', [NotificationController::class, 'adminNotifications'])->name('notifications');
        Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllReadAdmin'])->name('notifications.markAllRead');

    }); // End of Middleware Group

}); // End of Admin Prefix Group