<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use App\Models\Payout;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        if (!session()->has('loginusername')) {
            return redirect('/login');
        }

        $seller = $this->currentSeller();
        $sellerSkus = $this->sellerSkus($seller);

        // Get dates
        $today = Carbon::today();
        $sevenDaysAgo = Carbon::now()->subDays(7);
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        // Sales Summary - Today
        $todayOrders = Order::whereDate('created_at', $today)
            ->when($seller, function ($q) use ($sellerSkus) {
                $q->whereIn('sku', $sellerSkus);
            })
            ->get();
        $todaySales = $todayOrders->sum('total_price');
        $todayUnits = $todayOrders->sum('quantity');

        // Sales Summary - Last 7 Days
        $last7DaysOrders = Order::where('created_at', '>=', $sevenDaysAgo)
            ->when($seller, function ($q) use ($sellerSkus) {
                $q->whereIn('sku', $sellerSkus);
            })
            ->get();
        $last7DaysSales = $last7DaysOrders->sum('total_price');
        $last7DaysUnits = $last7DaysOrders->sum('quantity');

        // Sales Summary - Last 30 Days
        $last30DaysOrders = Order::where('created_at', '>=', $thirtyDaysAgo)
            ->when($seller, function ($q) use ($sellerSkus) {
                $q->whereIn('sku', $sellerSkus);
            })
            ->get();
        $last30DaysSales = $last30DaysOrders->sum('total_price');
        $last30DaysUnits = $last30DaysOrders->sum('quantity');

        // Orders Statistics
        $pendingOrders = Order::when($seller, function ($q) use ($sellerSkus) {
                $q->whereIn('sku', $sellerSkus);
            })
            ->where('status', 'pending')
            ->count();

        $unshippedOrders = Order::when($seller, function ($q) use ($sellerSkus) {
                $q->whereIn('sku', $sellerSkus);
            })
            ->where('status', 'unshipped')
            ->count();

        $returnRequests = Order::when($seller, function ($q) use ($sellerSkus) {
                $q->whereIn('sku', $sellerSkus);
            })
            ->where('status', 'returned')
            ->count();

        // Payments
        $sellerOrderIds = $seller ? Order::whereIn('sku', $sellerSkus)->pluck('order_id') : collect();

        $totalBalance = Payment::when($seller, function ($q) use ($sellerOrderIds) {
                $q->whereIn('order_id', $sellerOrderIds);
            })
            ->where('status', 'completed')
            ->sum('amount');

        // Next payout: use the Payout model's 7-day cycle
        $nextPayoutDate = $seller ? (Payout::getNextPayoutDate($seller->id) ?? 'N/A') : 'N/A';

        // Inventory
        $totalProducts = Product::when($seller, function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })
            ->count();

        $lowStockProducts = Product::when($seller, function ($q) use ($seller) {
                $q->where('seller_id', $seller->id);
            })
            ->where('quantity', '<', 10)
            ->count();

        // Calculate sales percentage for progress bar (assuming target is ₹50,000 for 30 days)
        $salesTarget = 50000;
        $salesPercentage = $last30DaysSales > 0 ? min(($last30DaysSales / $salesTarget) * 100, 100) : 0;

        return view('welcome', compact(
            'todaySales',
            'todayUnits',
            'last7DaysSales',
            'last7DaysUnits',
            'last30DaysSales',
            'last30DaysUnits',
            'pendingOrders',
            'unshippedOrders',
            'returnRequests',
            'totalBalance',
            'nextPayoutDate',
            'totalProducts',
            'lowStockProducts',
            'salesPercentage',
            'seller'
        ));
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
