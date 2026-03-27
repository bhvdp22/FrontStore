<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\Payout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->has('loginusername')) {
            return redirect('/login');
        }

        $seller = $this->currentSeller();
        $sellerId = $seller ? $seller->id : 0;
        $cacheKey = 'dashboard_' . $sellerId;

        // Cache dashboard data for 5 minutes (300 seconds)
        $data = Cache::remember($cacheKey, 300, function () use ($seller) {
            $sellerSkus = $this->sellerSkus($seller);

            $today = Carbon::today();
            $sevenDaysAgo = Carbon::now()->subDays(7);
            $thirtyDaysAgo = Carbon::now()->subDays(30);

            // Sales Summary - Today
            $todayOrders = Order::whereDate('created_at', $today)
                ->when($seller, fn($q) => $q->whereIn('sku', $sellerSkus))
                ->get();

            // Sales Summary - Last 7 Days
            $last7DaysOrders = Order::where('created_at', '>=', $sevenDaysAgo)
                ->when($seller, fn($q) => $q->whereIn('sku', $sellerSkus))
                ->get();

            // Sales Summary - Last 30 Days
            $last30DaysOrders = Order::where('created_at', '>=', $thirtyDaysAgo)
                ->when($seller, fn($q) => $q->whereIn('sku', $sellerSkus))
                ->get();

            // Orders Statistics (3 queries → 1 query)
            $orderCounts = Order::when($seller, fn($q) => $q->whereIn('sku', $sellerSkus))
                ->selectRaw("
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'unshipped' THEN 1 ELSE 0 END) as unshipped,
                    SUM(CASE WHEN status = 'returned' THEN 1 ELSE 0 END) as returned
                ")
                ->first();

            // Payments
            $sellerOrderIds = $seller ? Order::whereIn('sku', $sellerSkus)->pluck('order_id') : collect();
            $totalBalance = Payment::when($seller, fn($q) => $q->whereIn('order_id', $sellerOrderIds))
                ->where('status', 'completed')
                ->sum('amount');

            $nextPayoutDate = $seller ? (Payout::getNextPayoutDate($seller->id) ?? 'N/A') : 'N/A';

            // Inventory (2 queries → 1 query)
            $inventory = Product::when($seller, fn($q) => $q->where('seller_id', $seller->id))
                ->selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN quantity < 10 THEN 1 ELSE 0 END) as low_stock
                ")
                ->first();

            $last30DaysSales = $last30DaysOrders->sum('total_price');
            $salesTarget = 50000;

            return [
                'todaySales' => $todayOrders->sum('total_price'),
                'todayUnits' => $todayOrders->sum('quantity'),
                'last7DaysSales' => $last7DaysOrders->sum('total_price'),
                'last7DaysUnits' => $last7DaysOrders->sum('quantity'),
                'last30DaysSales' => $last30DaysSales,
                'last30DaysUnits' => $last30DaysOrders->sum('quantity'),
                'pendingOrders' => (int) ($orderCounts->pending ?? 0),
                'unshippedOrders' => (int) ($orderCounts->unshipped ?? 0),
                'returnRequests' => (int) ($orderCounts->returned ?? 0),
                'totalBalance' => $totalBalance,
                'nextPayoutDate' => $nextPayoutDate,
                'totalProducts' => (int) ($inventory->total ?? 0),
                'lowStockProducts' => (int) ($inventory->low_stock ?? 0),
                'salesPercentage' => $last30DaysSales > 0 ? min(($last30DaysSales / $salesTarget) * 100, 100) : 0,
            ];
        });

        // Merge seller into data for the view
        $data['seller'] = $seller;

        return view('welcome', $data);
    }

    private function currentSeller(): ?User
    {
        $username = session('loginusername');
        return $username ? User::where('name', $username)->first() : null;
    }

    private function sellerSkus(?User $seller)
    {
        return $seller
            ? Product::where('seller_id', $seller->id)->pluck('sku')
            : Product::pluck('sku');
    }
}
