<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\User;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Razorpay\Api\Api;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $seller = $this->currentSeller();
        $sellerSkus = $seller ? Product::where('seller_id', $seller->id)->pluck('sku') : Product::pluck('sku');

        $orders = Order::query()
            ->when($seller, function ($q) use ($sellerSkus) {
                $q->whereIn('sku', $sellerSkus);
            })
            ->when($search, function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->get();

        return view('orders.index', compact('orders', 'search'));
    }

    public function create()
    {
        $seller = $this->currentSeller();
        $products = Product::where('status', 'active')
            ->where('quantity', '>', 0)
            ->when($seller, function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })
            ->get();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $seller = $this->currentSeller();

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'sku' => ['required', 'string', 'exists:products,sku'], // Ensure SKU exists
            'quantity' => ['required', 'integer', 'min:1'],
            'status' => ['nullable', 'string', 'in:Unshipped,Shipped,Delivered,Cancelled'],
            'payment_method' => ['required', 'string', 'max:100'],
            'payment_status' => ['required', 'string', 'in:Pending,Completed'],
            'transaction_id' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::where('sku', $data['sku'])
            ->when($seller, function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })
            ->first();

        if (!$product) {
            return back()->withErrors(['sku' => 'You can only create orders for your own products.'])->withInput();
        }

        if ($product->quantity < $data['quantity']) {
            return back()->withErrors(['quantity' => 'Insufficient stock. Available: ' . $product->quantity])->withInput();
        }

        $unitPrice = $product->price;
        $totalPayment = $unitPrice * $data['quantity']; 
        
        $orderData = [
            'order_id'      => 'ORD-' . strtoupper(uniqid()), 
            'customer_name' => $data['customer_name'],
            'customer_email'=> $request->input('customer_email'),
            'product_name'  => $product->name,
            'sku'           => $product->sku,
            'quantity'      => $data['quantity'],
            'total_price'   => $totalPayment,
            'status'        => $data['status'] ?? 'Unshipped',
            'img_path'      => $product->img_path,
            'order_source'  => 'manual'
        ];

        DB::transaction(function () use ($orderData, $product, $data, $request) {

            $order = Order::create($orderData);

            // Save OrderItem for manual orders too
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'price'        => $product->price,
                'quantity'     => $data['quantity'],
            ]);

            $transactionId = $data['transaction_id'] ?? 'TXN-' . strtoupper(uniqid());

            Payment::create([
                'payment_id' => $transactionId,
                'order_id' => $orderData['order_id'],
                'amount' => $orderData['total_price'],
                'payment_method' => $data['payment_method'],
                'status' => $data['payment_status']
            ]);

            $product->decrement('quantity', $data['quantity']);
            if ($product->fresh()->quantity <= 0) {
                $product->update(['status' => 'inactive']);
            }

            // ── Notifications ──
            if ($product->seller_id) {
                \App\Services\NotificationService::newOrderForSeller(
                    $product->seller_id, $orderData['order_id'], $product->name, $orderData['total_price']
                );
            }
            \App\Services\NotificationService::newOrderForAdmin(
                $orderData['order_id'], $product->name, 0
            );

            // Low stock warning
            $freshProduct = $product->fresh();
            if ($freshProduct && $freshProduct->quantity > 0 && $freshProduct->quantity < 5 && $product->seller_id) {
                \App\Services\NotificationService::lowStockWarning(
                    $product->seller_id, $product->name, $freshProduct->quantity, $product->id
                );
            }
            });

        return redirect()->route('orders.index')->with('success', 'Order placed! Total: ₹' . number_format($totalPayment));
    }

    public function edit($id)
    {
        $order = $this->findOwnedOrder($id);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = $this->findOwnedOrder($id);

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:Unshipped,Shipped,Delivered,Cancelled'],
        ]);

        $order->update($data);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully');
    }

    public function destroy($id)
    {
        $order = $this->findOwnedOrder($id);
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully');
    }

    public function placeOrder(Request $request)
    {
        // 1. Validation
        $request->validate([
            'customer_name' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        if($cartItems->isEmpty()) return redirect()->back()->with('error', 'Cart is empty');

        // 2. Razorpay Verification
        if ($request->payment_method === 'razorpay') {
            
            $api = new Api(config('services.razorpay.key'), config('services.razorpay.secret'));

            try {
                // Verify the signature
                $attributes = [
                    'razorpay_order_id'   => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature'  => $request->razorpay_signature
                ];

                $api->utility->verifyPaymentSignature($attributes);
                // SUCCESS: Signature matches

            } catch (\Exception $e) {
                // FAILURE: Log the error to see WHY
                \Log::error('Razorpay Error: ' . $e->getMessage());
                \Log::error('Key Used: ' . config('services.razorpay.key'));
                
                return redirect()->back()->with('error', 'Payment verification failed. Signature mismatch or invalid payment.');
            }
        }

        // 3. Save Order Logic (Only runs if payment checks passed)
        $totalAmount = 0;
        foreach($cartItems as $item) {
            $totalAmount += $item->product->price * $item->quantity;
        }

        DB::transaction(function () use ($request, $cartItems, $totalAmount) {
            // Create Order
            $order = Order::create([
                'order_id'      => 'ORD-' . strtoupper(uniqid()),
                'customer_id'   => Auth::id(),
                'customer_name' => $request->customer_name,
                'total_price'   => $totalAmount,
                'quantity'      => $cartItems->sum('quantity'),
                'status'        => ($request->payment_method === 'razorpay') ? 'Paid' : 'Unshipped',
                'product_name'  => $cartItems->first()->product->name,
                'sku'           => $cartItems->first()->product->sku,
                'img_path'      => $cartItems->first()->product->img_path ?? null,
                'order_source'  => 'online',
            ]);

            // Save Items
            foreach($cartItems as $item) {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->product->price,
                    'quantity'     => $item->quantity
                ]);
                $item->product->decrement('quantity', $item->quantity);
            }

            // Save Payment
            $paymentData = [
                'payment_id'     => $request->payment_method === 'razorpay' ? $request->razorpay_payment_id : 'COD-' . uniqid(),
                'order_id'       => $order->order_id,
                'amount'         => $totalAmount,
                'payment_method' => $request->payment_method === 'razorpay' ? 'Razorpay (Online)' : 'Cash on Delivery',
                'status'         => $request->payment_method === 'razorpay' ? 'Completed' : 'Pending'
            ];
            
            // Add Razorpay fields if online payment
            if ($request->payment_method === 'razorpay') {
                $paymentData['razorpay_payment_id'] = $request->razorpay_payment_id;
                $paymentData['razorpay_order_id'] = $request->razorpay_order_id;
                $paymentData['razorpay_signature'] = $request->razorpay_signature;
            }
            
            Payment::create($paymentData);

            Cart::where('user_id', Auth::id())->delete();
        });

        return view('order_success');
    }

    private function currentSeller(): ?User
    {
        $username = session('loginusername');
        return $username ? User::where('name', $username)->first() : null;
    }

    private function findOwnedOrder($id): Order
    {
        $seller = $this->currentSeller();
        $sellerSkus = $seller ? Product::where('seller_id', $seller->id)->pluck('sku') : Product::pluck('sku');

        $order = Order::where('id', $id)
            ->when($seller, function ($q) use ($sellerSkus) {
                $q->whereIn('sku', $sellerSkus);
            })
            ->first();

        if (!$order) {
            abort(403, 'You are not allowed to access this order.');
        }

        return $order;
    }

    public function show($id)
    {
        $order = $this->findOwnedOrder($id);
        $product = Product::where('sku', $order->sku)->first();
        
        return view('orders.show', compact('order', 'product'));
    }

    public function printPackingSlip($id)
    {
        $order = $this->findOwnedOrder($id);
        $product = Product::where('sku', $order->sku)->first();
        $seller = $this->currentSeller();
        
        return view('orders.packing-slip', compact('order', 'product', 'seller'));
    }
}
