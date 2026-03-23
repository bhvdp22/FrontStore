<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;

class AdminCustomerController extends Controller
{
    // 1. List all customers
    public function index(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Customer::count(),
            'with_orders' => Customer::whereHas('orders')->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats', 'search'));
    }

    // 2. Customer details
    public function show($id)
    {
        $customer = Customer::with(['addresses'])->findOrFail($id);
        
        $orders = Order::where('customer_email', $customer->email)
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total_orders' => $orders->count(),
            'total_spent' => $orders->sum('grand_total') ?: $orders->sum('total_price'),
            'last_order' => $orders->first()?->created_at,
        ];

        return view('admin.customers.show', compact('customer', 'orders', 'stats'));
    }
}
