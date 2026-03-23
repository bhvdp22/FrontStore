<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Review;
use App\Services\SellerReputationService;

class SellerStorefrontController extends Controller
{
    /**
     * Public seller storefront page – read-only for customers.
     */
    public function show(Request $request, string $slug, SellerReputationService $reputationService)
    {
        $seller = User::where('slug', $slug)
            ->where('storefront_enabled', true)
            ->whereRaw("LOWER(status) = 'active'")
            ->first();

        if (!$seller) {
            abort(404, 'Seller storefront not found.');
        }

        // ── Reputation data ────────────────────────────────────────
        $reputation = $reputationService->calculate((int) $seller->id);

        // ── Trust metrics ──────────────────────────────────────────
        $totalOrdersFulfilled = (int) Order::where('seller_id', $seller->id)
            ->whereIn('status', ['Delivered', 'delivered', 'Completed', 'completed'])
            ->count();

        $reviewStats = Review::query()
            ->join('products', 'products.id', '=', 'reviews.product_id')
            ->where('products.seller_id', $seller->id)
            ->where('reviews.status', 'approved')
            ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(reviews.id) as review_count')
            ->first();

        $avgRating   = $reviewStats->review_count > 0 ? round((float) $reviewStats->avg_rating, 1) : null;
        $reviewCount = (int) ($reviewStats->review_count ?? 0);

        $returnRate = $reputation['breakdown']['return_rate'];

        $trustMetrics = [
            'reputation_score' => $reputation['score'],
            'reputation_badge' => $reputation['badge'],
            'avg_rating'       => $avgRating,
            'review_count'     => $reviewCount,
            'return_rate'      => $returnRate,
            'orders_fulfilled' => $totalOrdersFulfilled,
            'member_since'     => $seller->created_at,
        ];

        // ── Products (active + in-stock by default, paginated) ─────
        $sortBy = $request->query('sort', 'newest');
        $query  = Product::where('seller_id', $seller->id)
            ->where('quantity', '>', 0);

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
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->appends($request->query());

        // ── SEO meta ───────────────────────────────────────────────
        $seo = [
            'title'     => ($seller->business_name ?: $seller->name) . ' – Seller Storefront | Front Store',
            'description' => \Illuminate\Support\Str::limit(strip_tags($seller->brand_story ?? ''), 160, '...'),
            'canonical' => route('seller.storefront', $slug),
        ];

        return view('seller.storefront', compact(
            'seller',
            'trustMetrics',
            'products',
            'sortBy',
            'seo'
        ));
    }
}
