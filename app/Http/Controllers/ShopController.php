<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Campaign;
use App\Models\Category;
use App\Services\SellerReputationService;
use App\Mail\OrderConfirmationMail;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categorySlug = $request->query('category');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $sortBy = $request->query('sort', 'newest');
        $inStockOnly = $request->query('in_stock');
        
        // 1. Get all categories (from admin) with product counts
        $categories = Category::withCount(['products' => function ($q) {
            $q->where('quantity', '>', 0);
        }])->orderBy('name')->get();
        
        // 2. Get products from the database (with seller & category)
        $query = Product::with(['seller', 'category']);
        
        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('sku', 'like', '%' . $search . '%');
            });
        }
        
        // Apply category filter
        $activeCategory = null;
        if ($categorySlug) {
            $activeCategory = Category::where('slug', $categorySlug)->first();
            if ($activeCategory) {
                $query->where('category_id', $activeCategory->id);
            }
        }
        
        // Apply price filters
        if ($minPrice && is_numeric($minPrice)) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice && is_numeric($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }
        
        // Apply in-stock filter
        if ($inStockOnly) {
            $query->where('quantity', '>', 0);
        }
        
        // Apply sorting
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name_az':
                $query->orderBy('name', 'asc');
                break;
            case 'name_za':
                $query->orderBy('name', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        $allProducts = $query->get();

        // 3. Mark products with active campaigns as sponsored
        $today = now()->toDateString();
        $campaignSkus = Campaign::where('status', 'Active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->pluck('sku')
            ->toArray();
        $sponsoredProducts = [];
        $regularProducts = [];

        foreach ($allProducts as $product) {
            $product->is_sponsored = \in_array($product->sku, $campaignSkus);
            if ($product->is_sponsored) {
                $sponsoredProducts[] = $product;
            } else {
                $regularProducts[] = $product;
            }
        }

        // Only re-sort by sponsored if not using a custom sort
        if ($sortBy === 'newest') {
            usort($sponsoredProducts, function ($a, $b) {
                return ($b->created_at ?? now()) <=> ($a->created_at ?? now());
            });
            usort($regularProducts, function ($a, $b) {
                return ($b->created_at ?? now()) <=> ($a->created_at ?? now());
            });
        }

        // Merge sponsored first
        $products = array_merge($sponsoredProducts, $regularProducts);

        // Attach seller reputation score/badge (read-only enrichment for UI)
        app(SellerReputationService::class)->attachReputationToProducts($products);

        // 4. Prepare image URLs in a separate array
        $placeholder = 'https://placehold.co/200?text=No+Image';
        $productImages = [];
        foreach ($products as $product) {
            $productImages[$product->id] = $product->img_path ?: $placeholder;
        }
        
        // 5. Get price range for filter
        $priceRange = [
            'min' => (int) Product::min('price'),
            'max' => (int) Product::max('price'),
        ];

        // 6. Return view
        return view('shop.index', compact(
            'products', 'productImages', 'search', 'categories', 'activeCategory',
            'categorySlug', 'minPrice', 'maxPrice', 'sortBy',
            'inStockOnly', 'priceRange'
        ));
    }

    public function cart()
    {
        $cart = session()->get('cart', []);
        
        // Calculate fees for cart display
        $feeCalculator = new \App\Services\FeeCalculator();
        $fees = $feeCalculator->calculateForCart($cart);
        $breakdown = $feeCalculator->getDisplayBreakdown($fees['subtotal']);
        
        return view('shop.cart', compact('cart', 'fees', 'breakdown'));
    }

    public function addToCart(Request $request)
    {
        $productId = $request->product_id;
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        // Check if product is in stock
        if ($product->quantity <= 0) {
            return response()->json(['success' => false, 'message' => 'Sorry, this product is out of stock!']);
        }

        $cart = session()->get('cart', []);

        // Check if adding more would exceed available stock
        $currentQtyInCart = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;
        if ($currentQtyInCart + 1 > $product->quantity) {
            return response()->json(['success' => false, 'message' => 'Sorry, only ' . $product->quantity . ' item(s) available in stock!']);
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'sku' => $product->sku,
                'image' => $product->img_path,
            ];
        }

        session()->put('cart', $cart);
        return response()->json(['success' => true, 'message' => 'Product added to cart!', 'cartCount' => count($cart)]);
    }

    public function updateCart(Request $request)
    {
        if ($request->id && $request->quantity) {
            $product = Product::find($request->id);
            
            // Check if product exists and has enough stock
            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product no longer available']);
            }
            
            if ($request->quantity > $product->quantity) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Only ' . $product->quantity . ' item(s) available in stock',
                    'max_quantity' => $product->quantity
                ]);
            }
            
            $cart = session()->get('cart');
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function removeFromCart(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['success' => true, 'cart_count' => count($cart)]);
        }
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty!');
        }

        // Validate stock availability for all cart items before checkout
        $outOfStockItems = [];
        $updatedCart = $cart;
        
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            
            if (!$product) {
                // Product was deleted
                unset($updatedCart[$productId]);
                $outOfStockItems[] = $item['name'] . ' (no longer available)';
            } elseif ($product->quantity <= 0) {
                // Product is out of stock
                unset($updatedCart[$productId]);
                $outOfStockItems[] = $item['name'] . ' (out of stock)';
            } elseif ($product->quantity < $item['quantity']) {
                // Not enough stock - adjust quantity
                $updatedCart[$productId]['quantity'] = $product->quantity;
                $outOfStockItems[] = $item['name'] . ' (only ' . $product->quantity . ' available, adjusted quantity)';
            }
        }
        
        // Update cart if items were removed or adjusted
        if (!empty($outOfStockItems)) {
            session()->put('cart', $updatedCart);
            $cart = $updatedCart;
            
            if (empty($cart)) {
                return redirect()->route('shop.cart')->with('error', 'All items in your cart are no longer available: ' . implode(', ', $outOfStockItems));
            }
            
            return redirect()->route('shop.cart')->with('error', 'Some items were updated due to stock changes: ' . implode(', ', $outOfStockItems));
        }

        // Calculate fees using FeeCalculator
        $feeCalculator = new \App\Services\FeeCalculator();
        $fees = $feeCalculator->calculateForCart($cart);
        $breakdown = $feeCalculator->getDisplayBreakdown($fees['subtotal']);

        // Get saved addresses if customer is logged in
        $savedAddresses = collect();
        $customer = null;
        if (session()->has('customer_id')) {
            $customer = \App\Models\Customer::find(session('customer_id'));
            if ($customer) {
                $savedAddresses = \App\Models\CustomerAddress::where('customer_id', $customer->id)
                    ->orderByDesc('is_default')
                    ->orderByDesc('created_at')
                    ->get();
            }
        }

        return view('shop.checkout', compact('cart', 'fees', 'breakdown', 'savedAddresses', 'customer'));
    }

    public function placeOrder(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string|max:100',
            'razorpay_payment_id' => 'nullable|string',
            'razorpay_order_id' => 'nullable|string',
            'razorpay_signature' => 'nullable|string',
        ]);

        // If Razorpay selected, only enforce payment_id; order_id/signature may be absent in some flows
        if ($validated['payment_method'] === 'razorpay') {
            $request->validate([
                'razorpay_payment_id' => 'required|string',
            ]);
        }

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Your cart is empty!');
        }

        // ============================================================
        // CRITICAL: Validate stock with ROW LOCKING before order
        // This prevents race condition where two customers buy same item
        // ============================================================
        
        try {
            return DB::transaction(function () use ($validated, $cart, $request) {
                
                // Lock the product rows to prevent concurrent purchases
                $outOfStockItems = [];
                $productsToUpdate = [];
                
                foreach ($cart as $productId => $item) {
                    // Lock the row for update - this prevents other transactions from reading/updating
                    $product = Product::where('id', $productId)->lockForUpdate()->first();
                    
                    if (!$product) {
                        $outOfStockItems[] = $item['name'] . ' (no longer available)';
                        continue;
                    }
                    
                    if ($product->quantity <= 0) {
                        $outOfStockItems[] = $item['name'] . ' (out of stock - someone else purchased it)';
                        continue;
                    }
                    
                    if ($product->quantity < $item['quantity']) {
                        $outOfStockItems[] = $item['name'] . ' (only ' . $product->quantity . ' available now)';
                        continue;
                    }
                    
                    // Product is available - store for later update
                    $productsToUpdate[$productId] = [
                        'product' => $product,
                        'item' => $item
                    ];
                }
                
                // If any items are out of stock, abort transaction and redirect
                if (!empty($outOfStockItems)) {
                    // Remove out-of-stock items from cart
                    $updatedCart = [];
                    foreach ($cart as $productId => $item) {
                        if (isset($productsToUpdate[$productId])) {
                            $updatedCart[$productId] = $item;
                        }
                    }
                    session()->put('cart', $updatedCart);
                    
                    throw new \Exception('Stock unavailable: ' . implode(', ', $outOfStockItems));
                }

                // All products are available - proceed with order
                
                // Calculate total amount
                $totalAmount = 0;
                foreach ($cart as $item) {
                    $totalAmount += $item['price'] * $item['quantity'];
                }

                // Generate unique order ID for this transaction
                $orderId = 'ORD-' . strtoupper(uniqid());

                // Determine payment status and transaction ID
                if ($validated['payment_method'] == 'razorpay') {
                    // Verify Razorpay payment signature
                    if (!$this->verifyRazorpaySignature($request)) {
                        \Log::warning('Payment verification failed', [
                            'payment_id' => $validated['razorpay_payment_id'],
                            'order_id' => $validated['razorpay_order_id']
                        ]);
                        throw new \Exception('Payment verification failed. Please try again.');
                    }
                    
                    $paymentStatus = 'Completed';
                    $transactionId = $validated['razorpay_payment_id'];
                    $paymentMethod = 'Razorpay (Online)';
                } else {
                    // Cash on Delivery
                    $paymentStatus = 'Pending';
                    $transactionId = 'COD-' . strtoupper(uniqid());
                    $paymentMethod = 'Cash on Delivery';
                }

                // Initialize fee calculator
                $feeCalculator = new \App\Services\FeeCalculator();

                // Create orders for each product in cart
                $itemIndex = 0;
                foreach ($productsToUpdate as $productId => $data) {
                    $product = $data['product'];
                    $item = $data['item'];
                    
                    // Generate unique order_id for each item but keep the base order ID the same
                    $itemOrderId = $itemIndex === 0 ? $orderId : $orderId . '-' . ($itemIndex + 1);
                    
                    // Calculate fees for this specific item
                    $itemSubtotal = $item['price'] * $item['quantity'];
                    $itemFees = $feeCalculator->calculate($itemSubtotal);
                    
                    Order::create([
                        'order_id' => $itemOrderId,
                        'customer_name' => $validated['customer_name'],
                        'customer_email' => $validated['customer_email'],
                        'customer_phone' => $validated['customer_phone'],
                        'shipping_address' => $validated['shipping_address'],
                        'product_name' => $item['name'],
                        'sku' => $item['sku'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $itemFees['subtotal'],
                        'tax_rate' => $itemFees['tax_rate'],
                        'tax_amount' => $itemFees['tax_amount'],
                        'platform_fee' => $itemFees['platform_fee'],
                        'commission_rate' => $itemFees['commission_rate'],
                        'commission_amount' => $itemFees['commission_amount'],
                        'seller_earnings' => $itemFees['seller_earnings'],
                        'total_price' => $itemFees['total'],
                        'grand_total' => $itemFees['total'],
                        'seller_id' => $product->seller_id,
                        'status' => 'Unshipped',
                        'img_path' => $item['image'],
                        'order_source' => 'online',
                    ]);

                    // Save OrderItem for this product
                    $savedOrder = Order::where('order_id', $itemOrderId)->first();
                    OrderItem::create([
                        'order_id'     => $savedOrder->id,
                        'product_id'   => $productId,
                        'product_name' => $item['name'],
                        'price'        => $item['price'],
                        'quantity'     => $item['quantity'],
                    ]);

                    // Reduce inventory for the ordered product (already locked)
                    $product->decrement('quantity', $item['quantity']);
                    
                    // Mark as inactive if out of stock
                    if ($product->fresh()->quantity <= 0) {
                        $product->update(['status' => 'inactive']);
                    }
                    
                    $itemIndex++;
                }

                // Calculate total with all fees for payment record
                $totalFees = $feeCalculator->calculateForCart($cart);

                // Create payment record
                $paymentData = [
                    'payment_id' => $transactionId,
                    'order_id' => $orderId,
                    'amount' => $totalFees['total'],
                    'payment_method' => $paymentMethod,
                    'status' => $paymentStatus,
                ];
        
                // Add Razorpay fields if online payment
                if ($validated['payment_method'] === 'razorpay') {
                    $paymentData['razorpay_payment_id'] = $validated['razorpay_payment_id'] ?? null;
                    $paymentData['razorpay_order_id'] = $validated['razorpay_order_id'] ?? session('razorpay_order_id');
                    $paymentData['razorpay_signature'] = $validated['razorpay_signature'] ?? null;
                }
                
                \App\Models\Payment::create($paymentData);

                // ── Notifications ──
                foreach ($productsToUpdate as $prodId => $data) {
                    $prod = $data['product'];
                    $itm = $data['item'];
                    $itemTotal = $itm['price'] * $itm['quantity'];
                    $itemFees = $feeCalculator->calculate($itemTotal);

                    // Notify seller: New Order
                    if ($prod->seller_id) {
                        \App\Services\NotificationService::newOrderForSeller(
                            $prod->seller_id, $orderId, $itm['name'], $itemTotal
                        );
                    }

                    // Notify admin: New Sale (commission earned)
                    \App\Services\NotificationService::newOrderForAdmin(
                        $orderId, $itm['name'], $itemFees['commission_amount'] ?? 0
                    );

                    // Low stock warning (< 5)
                    $freshProd = $prod->fresh();
                    if ($freshProd && $freshProd->quantity > 0 && $freshProd->quantity < 5 && $prod->seller_id) {
                        \App\Services\NotificationService::lowStockWarning(
                            $prod->seller_id, $prod->name, $freshProd->quantity, $prod->id
                        );
                    }
                }

                // ── Send Order Confirmation Email with Invoice ──
                try {
                    $orderData = [
                        'order_id'         => $orderId,
                        'invoice_number'   => 'INV-' . str_replace('ORD-', '', $orderId),
                        'customer_name'    => $validated['customer_name'],
                        'customer_email'   => $validated['customer_email'],
                        'customer_phone'   => $validated['customer_phone'],
                        'shipping_address' => $validated['shipping_address'],
                        'order_date'       => now()->format('d M Y, h:i A'),
                        'payment_method'   => $paymentMethod,
                        'payment_status'   => $paymentStatus,
                        'items'            => collect($productsToUpdate)->map(function ($data) {
                            return [
                                'name'     => $data['item']['name'],
                                'sku'      => $data['item']['sku'],
                                'price'    => $data['item']['price'],
                                'quantity' => $data['item']['quantity'],
                            ];
                        })->values()->toArray(),
                        'fees' => $totalFees,
                    ];

                    Mail::to($validated['customer_email'])->send(new OrderConfirmationMail($orderData));
                } catch (\Exception $mailEx) {
                    \Log::error('Order confirmation email failed: ' . $mailEx->getMessage());
                }

                // Clear cart and session data
                session()->forget('cart');
                session()->forget('razorpay_order_id');
                session()->forget('customer_data');

                // Store customer email in session for order tracking
                session(['customer_email' => $validated['customer_email']]);

                // Store product IDs for review links (get first product from cart as primary)
                $productIds = array_keys($cart);
                $primaryProductId = !empty($productIds) ? $productIds[0] : null;

                return redirect()->route('shop.success')
                    ->with('success', 'Order placed successfully!')
                    ->with('order_id', $orderId)
                    ->with('payment_status', $paymentStatus)
                    ->with('product_id', $primaryProductId);
                    
            }); // End of DB::transaction
            
        } catch (\Exception $e) {
            \Log::error('Order placement failed: ' . $e->getMessage());
            return redirect()->route('shop.checkout')->with('error', $e->getMessage());
        }
    }

    private function verifyRazorpaySignature(Request $request)
    {
        try {
            $razorpayKey = config('services.razorpay.key');
            $razorpaySecret = config('services.razorpay.secret');
            
            // Check if keys are configured
            if (empty($razorpayKey) || empty($razorpaySecret)) {
                \Log::error('Razorpay keys not properly configured', [
                    'key' => $razorpayKey,
                    'has_secret' => !empty($razorpaySecret)
                ]);
                return false;
            }
            
            $api = new \Razorpay\Api\Api($razorpayKey, $razorpaySecret);

            // Normalize inputs (handle literal "undefined" strings from frontend)
            $requestOrderId = $request->razorpay_order_id;
            $requestSignature = $request->razorpay_signature;

            if ($requestOrderId === 'undefined') {
                $requestOrderId = null;
            }
            if ($requestSignature === 'undefined') {
                $requestSignature = null;
            }

            // Prefer request order id, fallback to session
            $expectedOrderId = $requestOrderId ?: session('razorpay_order_id');
            // Expected amount (paise) from session
            $expectedAmountPaise = session()->has('amount') ? (int) round(session('amount') * 100) : null;

            // If signature is present, verify normally
            if (!empty($requestSignature) && !empty($requestOrderId)) {
                $attributes = [
                    'razorpay_order_id' => $requestOrderId,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $requestSignature
                ];

                $expectedSignature = hash_hmac('sha256', $attributes['razorpay_order_id'] . '|' . $attributes['razorpay_payment_id'], $razorpaySecret);
                \Log::info('Razorpay signature debug', [
                    'provided_signature' => $attributes['razorpay_signature'] ?? 'null',
                    'expected_signature' => $expectedSignature,
                    'order_id' => $attributes['razorpay_order_id'],
                    'payment_id' => $attributes['razorpay_payment_id'],
                ]);

                $api->utility->verifyPaymentSignature($attributes);
                return true;
            }

            // Fallback: No signature returned. Fetch payment details and validate.
            if (!$request->filled('razorpay_payment_id')) {
                \Log::error('Razorpay verification failed: payment_id missing', [
                    'order_id' => $expectedOrderId,
                ]);
                return false;
            }

            if (empty($expectedOrderId)) {
                \Log::error('Razorpay verification failed: order_id missing', [
                    'request_order_id' => $requestOrderId,
                    'session_order_id' => session('razorpay_order_id'),
                ]);
                return false;
            }

            $payment = $api->payment->fetch($request->razorpay_payment_id);

            \Log::info('Razorpay fallback fetch', [
                'payment_id' => $payment['id'] ?? null,
                'order_id' => $payment['order_id'] ?? null,
                'status' => $payment['status'] ?? null,
                'captured' => $payment['captured'] ?? null,
                'amount' => $payment['amount'] ?? null,
                'expected_order_id' => $expectedOrderId,
                'expected_amount_paise' => $expectedAmountPaise,
            ]);

            // Validate order match and status captured
            $paymentOrderId = $payment['order_id'] ?? null;
            $paymentAmount = $payment['amount'] ?? null;
            $paymentStatus = $payment['status'] ?? null;

            // If order id present, it must match
            if (!empty($paymentOrderId) && !empty($expectedOrderId) && $paymentOrderId !== $expectedOrderId) {
                \Log::error('Razorpay fallback validation failed: order mismatch', [
                    'expected_order_id' => $expectedOrderId,
                    'payment_order_id' => $paymentOrderId,
                ]);
                return false;
            }

            // If order id missing, rely on amount check
            if (empty($paymentOrderId) && !empty($expectedOrderId)) {
                if (!empty($expectedAmountPaise) && $paymentAmount != $expectedAmountPaise) {
                    \Log::error('Razorpay fallback validation failed: order missing and amount mismatch', [
                        'expected_amount_paise' => $expectedAmountPaise,
                        'payment_amount' => $paymentAmount,
                    ]);
                    return false;
                }
            }

            // Ensure captured; if authorized and amount matches, attempt capture
            if ($paymentStatus === 'authorized') {
                if (!empty($expectedAmountPaise) && $paymentAmount != $expectedAmountPaise) {
                    \Log::error('Razorpay fallback validation failed: authorized but amount mismatch', [
                        'expected_amount_paise' => $expectedAmountPaise,
                        'payment_amount' => $paymentAmount,
                    ]);
                    return false;
                }

                try {
                    $payment->capture(['amount' => $paymentAmount]);
                    $paymentStatus = 'captured';
                } catch (\Exception $captureException) {
                    \Log::error('Razorpay fallback capture failed', [
                        'error' => $captureException->getMessage(),
                        'payment_id' => $payment['id'] ?? null,
                    ]);
                    return false;
                }
            }

            if ($paymentStatus !== 'captured') {
                \Log::error('Razorpay fallback validation failed: status not captured', [
                    'status' => $paymentStatus,
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('Razorpay signature verification failed', [
                'error' => $e->getMessage(),
                'payment_id' => $request->razorpay_payment_id,
                'order_id' => $request->razorpay_order_id
            ]);
            return false;
        }
    }

    public function success()
    {
        return view('shop.success');
    }

    public function help()
    {
        return view('shop.help');
    }

    public function product($id)
    {
        $product = Product::with('seller')->findOrFail($id);

        // Flag sponsored if there's an active campaign for this SKU
        $today = now()->toDateString();
        $product->is_sponsored = Campaign::where('sku', $product->sku)
            ->where('status', 'Active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->exists();

        // Image fallback
        $placeholder = 'https://placehold.co/400x300?text=No+Image';
        $imageUrl = $product->img_path ?: $placeholder;

        // Attach seller reputation score/badge for product detail view
        app(SellerReputationService::class)->attachReputationToProducts([$product]);

        return view('shop.product', compact('product', 'imageUrl'));
    }
}