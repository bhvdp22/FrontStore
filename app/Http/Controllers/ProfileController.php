<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        if (!session()->has('customer_email')) {
            return redirect()->route('customer.login');
        }

        $customer = \App\Models\Customer::where('email', session('customer_email'))->first();
        if (!$customer) {
            return redirect()->route('profile.index')->with('error', 'Customer not found.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email,' . $customer->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        if (!empty($validated['password'])) {
            $customer->password = bcrypt($validated['password']);
        }
        $customer->save();

        // Update session email if changed
        session(['customer_email' => $customer->email]);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }
    public function index()
    {
        if (!session()->has('customer_email')) {
            return redirect()->route('customer.login');
        }
        
        $customer = Customer::where('email', session('customer_email'))->first();
        
        // Create customer record if doesn't exist
        if (!$customer) {
            $customer = Customer::create([
                'email' => session('customer_email'),
                'name' => session('customer_name', 'Customer'),
                'password' => bcrypt('default_password'), // They can change it later
            ]);
        }
        
        // Get order count for this customer
        $orderCount = Order::where('customer_email', session('customer_email'))->count();
        
        // Get review count
        $reviewCount = \App\Models\Review::where('customer_email', session('customer_email'))->count();
        
        return view('profile.index', compact('customer', 'orderCount', 'reviewCount'));
    }
    public function orders(Request $request)
    {
        // Get email from session (stored during checkout) or from query parameter
        $email = session('customer_email') ?? $request->query('email');
        
        if (!$email) {
            return redirect()->route('shop.index')->with('error', 'Please provide your email to view orders.');
        }
        
        // Store email in session for future requests
        session(['customer_email' => $email]);
        
        // Query orders for this customer, grouped by base order_id (without item suffix)
        $orders = Order::where('customer_email', $email)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($order) {
                // Extract base order ID (remove -2, -3 suffixes if present)
                return preg_replace('/-\d+$/', '', $order->order_id);
            });

        // Map SKUs to products for review actions
        $skus = $orders->flatten()->pluck('sku')->filter()->unique();
        $productsBySku = Product::whereIn('sku', $skus)->get()->keyBy('sku');
        
        return view('profile.orders', compact('orders', 'email', 'productsBySku'));
    }
    
    public function track($orderId)
    {
        $email = session('customer_email');
        
        if (!$email) {
            return redirect()->route('profile.orders')->with('error', 'Session expired. Please login again.');
        }
        
        // Get all items for this order (including items with suffix like ORD-ABC-2, ORD-ABC-3)
        $orderItems = Order::with('payment')
            ->where('customer_email', $email)
            ->where(function ($query) use ($orderId) {
                $query->where('order_id', $orderId)
                      ->orWhere('order_id', 'LIKE', $orderId . '-%');
            })
            ->get();
            
        if ($orderItems->isEmpty()) {
            return redirect()->route('profile.orders')->with('error', 'Order not found.');
        }
        
        return view('profile.track', compact('orderItems', 'orderId'));
    }

    public function reviews()
    {
        $email = session('customer_email');
        if (!$email) {
            return redirect()->route('customer.login');
        }

        $reviews = \App\Models\Review::where('customer_email', $email)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('profile.reviews', compact('reviews'));
    }
}
