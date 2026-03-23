<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Campaign;

class AdminProductController extends Controller
{
    // 1. List all products across all sellers
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'category']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(20);

        // Count sponsored products via active campaigns
        $sponsoredSkus = Campaign::where('status', 'Active')->pluck('sku');
        $sponsoredCount = $sponsoredSkus->isNotEmpty()
            ? Product::whereIn('sku', $sponsoredSkus)->count()
            : 0;

        $stats = [
            'total' => Product::count(),
            'active' => Product::where('status', 'active')->count(),
            'out_of_stock' => Product::where('quantity', 0)->count(),
            'sponsored' => $sponsoredCount,
        ];

        return view('admin.products.index', compact('products', 'stats', 'search', 'status'));
    }

    // 2. Product details
    public function show($id)
    {
        $product = Product::with(['seller', 'category', 'reviews'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }
}
