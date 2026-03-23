<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $sellerId = session('loginId');
        if (!$sellerId) {
            return redirect()->route('login');
        }

        // Get seller's SKUs
        $sellerSkus = Product::where('seller_id', $sellerId)->pluck('sku');

        // Date range filter
        $startDate = $request->input('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        // Sales Summary
        $salesData = Order::whereIn('sku', $sellerSkus)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(quantity) as total_units,
                SUM(total_price) as total_revenue
            ')
            ->first();

        // Returns Summary
        $returnsData = ProductReturn::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_returns,
                SUM(quantity) as total_units_returned,
                SUM(refund_amount) as total_refund_amount,
                SUM(CASE WHEN status = "refund_completed" THEN 1 ELSE 0 END) as completed_refunds
            ')
            ->first();

        // Top Products by Sales
        $topProducts = Order::whereIn('sku', $sellerSkus)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('product_name', 'sku', DB::raw('SUM(quantity) as units_sold'), DB::raw('SUM(total_price) as revenue'))
            ->groupBy('product_name', 'sku')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // Top Returned Products
        $topReturnedProducts = ProductReturn::where('returns.seller_id', $sellerId)
            ->whereBetween('returns.created_at', [$startDate, $endDate])
            ->join('products', 'returns.product_id', '=', 'products.id')
            ->select('products.name', 'products.sku', DB::raw('COUNT(*) as return_count'), DB::raw('SUM(returns.refund_amount) as total_refunded'))
            ->groupBy('products.name', 'products.sku')
            ->orderByDesc('return_count')
            ->limit(10)
            ->get();

        // Returns by Reason
        $returnsByReason = ProductReturn::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('return_reason', DB::raw('COUNT(*) as count'))
            ->groupBy('return_reason')
            ->orderByDesc('count')
            ->get();

        // Returns by Status
        $returnsByStatus = ProductReturn::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->orderByDesc('count')
            ->get();

        // Payment Summary
        $paymentData = Payment::whereIn('order_id', function($query) use ($sellerSkus) {
                $query->select('order_id')
                    ->from('orders')
                    ->whereIn('sku', $sellerSkus);
            })
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                COUNT(*) as total_payments,
                SUM(amount) as total_amount,
                SUM(CASE WHEN status = "Completed" THEN amount ELSE 0 END) as completed_amount,
                SUM(CASE WHEN payment_method LIKE "%Razorpay%" THEN 1 ELSE 0 END) as online_payments,
                SUM(CASE WHEN payment_method LIKE "%Cash%" THEN 1 ELSE 0 END) as cod_payments
            ')
            ->first();

        // Low Stock Products (less than 10 units)
        $lowStockProducts = Product::where('seller_id', $sellerId)
            ->where('quantity', '>', 0)
            ->where('quantity', '<', 10)
            ->orderBy('quantity', 'asc')
            ->get();

        // Out of Stock Products
        $outOfStockProducts = Product::where('seller_id', $sellerId)
            ->where('quantity', '<=', 0)
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('reports.index', compact(
            'salesData',
            'returnsData',
            'topProducts',
            'topReturnedProducts',
            'returnsByReason',
            'returnsByStatus',
            'paymentData',
            'lowStockProducts',
            'outOfStockProducts',
            'startDate',
            'endDate'
        ));
    }
}
