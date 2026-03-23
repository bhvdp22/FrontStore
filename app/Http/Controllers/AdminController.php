<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;

class AdminController extends Controller
{
    // 1. Show Login Form
    public function showLoginForm()
    {
        return view('admin.login');
    }

    // 2. Handle Login Logic
    public function login(Request $request)
    {
        // Validate inputs
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login using the 'admin' guard
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            // Success! Redirect to dashboard
            return redirect()->route('admin.dashboard');
        }

        // Failure! Go back with error
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // 3. Show Dashboard
    public function dashboard()
    {
        // 1. Count Total Active Sellers/Farmers
        $totalSellers = User::where('status', 'active')->count();

        // 2. Count Pending Approvals (The number you need to work on)
        $pendingSellers = User::where('status', 'pending')->count();

        // 3. Count Total Categories
        $totalCategories = Category::count();

        // 4. Count Total Products
        $totalProducts = Product::count();

        // 5. Count Total Orders
        $totalOrders = Order::count();

        // 6. Calculate Admin Revenue (Commission + Platform Fees + Tax collected)
        $totalCommission = Order::sum('commission_amount');
        $totalPlatformFees = Order::sum('platform_fee');
        $totalTaxCollected = Order::sum('tax_amount');
        $totalRevenue = $totalCommission + $totalPlatformFees + $totalTaxCollected;

        // 7. Total Order Value (what customers paid)
        $totalOrderValue = Order::sum('total_price');

        // 8. Total Seller Earnings
        $totalSellerEarnings = Order::sum('seller_earnings');

        // 9. Recent Orders
        $recentOrders = Order::orderBy('created_at', 'desc')->take(10)->get();

        // 10. Monthly Revenue (Last 6 months) for charts
        $monthlyRevenue = $this->getMonthlyRevenue();

        // 11. Orders by Status for doughnut chart
        $ordersByStatus = $this->getOrdersByStatus();

        // 12. Monthly Orders Count for bar chart
        $monthlyOrders = $this->getMonthlyOrders();

        // 13. Top Selling Products
        $topProducts = $this->getTopProducts();

        // 14. Top Sellers
        $topSellers = $this->getTopSellers();

        return view('admin.dashboard', compact(
            'totalSellers', 
            'pendingSellers', 
            'totalCategories', 
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'totalCommission',
            'totalPlatformFees',
            'totalTaxCollected',
            'totalOrderValue',
            'totalSellerEarnings',
            'recentOrders',
            'monthlyRevenue',
            'ordersByStatus',
            'monthlyOrders',
            'topProducts',
            'topSellers'
        ));
    }

    /**
     * Get monthly revenue & commission for last 6 months
     */
    private function getMonthlyRevenue()
    {
        $months = collect([]);
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('grand_total');
            $commission = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('commission_amount');
            $months->push([
                'month' => $date->format('M Y'),
                'revenue' => round((float)$revenue, 2),
                'commission' => round((float)$commission, 2),
            ]);
        }
        return $months;
    }

    /**
     * Get order counts grouped by status
     */
    private function getOrdersByStatus()
    {
        return Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    /**
     * Get monthly order counts for last 6 months
     */
    private function getMonthlyOrders()
    {
        $months = collect([]);
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $count = Order::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $months->push([
                'month' => $date->format('M Y'),
                'count' => $count,
            ]);
        }
        return $months;
    }

    /**
     * Get top 5 selling products by quantity sold
     */
    private function getTopProducts()
    {
        return DB::table('order_items')
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }

    /**
     * Get top 5 sellers by total earnings
     */
    private function getTopSellers()
    {
        return DB::table('orders')
            ->join('user', 'orders.seller_id', '=', 'user.id')
            ->select(
                'user.name',
                'user.business_name',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.seller_earnings) as total_earnings')
            )
            ->groupBy('user.id', 'user.name', 'user.business_name')
            ->orderByDesc('total_earnings')
            ->limit(5)
            ->get();
    }

    /**
     * API endpoint for real-time chart data refresh
     */
    public function chartData(Request $request)
    {
        $type = $request->get('type', 'revenue');
        switch ($type) {
            case 'revenue':
                return response()->json($this->getMonthlyRevenue());
            case 'orders':
                return response()->json($this->getMonthlyOrders());
            case 'status':
                return response()->json($this->getOrdersByStatus());
            case 'products':
                return response()->json($this->getTopProducts());
            default:
                return response()->json([]);
        }
    }

    // 4. Logout
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    // 5. All Orders List
    public function orders()
    {
        $orders = Order::with(['customer', 'seller', 'items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Summary stats
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::sum('commission_amount') + Order::sum('platform_fee') + Order::sum('tax_amount'),
            'total_order_value' => Order::sum('grand_total'),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    // 6. Order Details
    public function orderShow($id)
    {
        $order = Order::with(['customer', 'seller', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
}