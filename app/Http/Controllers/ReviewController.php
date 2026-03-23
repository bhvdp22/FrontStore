<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Show review form (linked to logged-in customer profile)
    public function showForm(Product $product, Request $request)
    {
        // Require logged-in customer
        $customerEmail = session('customer_email');
        if (!$customerEmail) {
            return redirect()->route('customer.login');
        }

        // Only allow review if customer purchased AND the order is delivered
        $hasDelivered = Order::where('customer_email', $customerEmail)
            ->where('status', 'Delivered')
            ->whereHas('items', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })
            ->exists();

        // Fallback: legacy orders table matching SKU
        if (!$hasDelivered && $product->sku) {
            $hasDelivered = Order::where('customer_email', $customerEmail)
                ->where('status', 'Delivered')
                ->where('sku', $product->sku)
                ->exists();
        }

        if (!$hasDelivered) {
            return redirect()->route('shop.product', ['id' => $product->id])
                ->with('error', 'Only delivered purchases can be reviewed.');
        }

        // Pass optional order_id from query if present
        $orderId = $request->query('order');
        return view('review-form', ['product' => $product, 'order_id' => $orderId]);
    }

    // Store a new review
    public function store(Request $request)
    {
        \Log::info('Review store called', [
            'all_request_data' => $request->all(),
            'session_customer_email' => session('customer_email'),
        ]);
        // Require logged-in customer
        $customerEmail = session('customer_email');
        $customerName = session('customer_name');
        // Fallback: look up customer name by email if session name missing
        if (!$customerName) {
            $customerName = Customer::where('email', $customerEmail)->value('name');
        }
        if (!$customerEmail) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to submit a review.'
            ], 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            // orders table uses order_id (string) as business identifier
            'order_id' => 'nullable|exists:orders,order_id',
            'review_text' => 'required|string|max:5000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Map provided order code to the actual order row (id) if present
        // Note: order_id might be a grouped key (e.g., ORD-XXX) that matches orders like ORD-XXX, ORD-XXX-2, etc.
        $orderRecord = null;
        $orderIdPattern = null;
        if (!empty($validated['order_id'])) {
            // First try exact match
            $orderRecord = Order::where('order_id', $validated['order_id'])->first();
            // If not found, prepare pattern for matching suffixed orders (e.g., ORD-XXX-2, ORD-XXX-3)
            if (!$orderRecord) {
                $orderIdPattern = $validated['order_id'];
            }
        }

        // Auto-fill fields from profile/session
        $rating = (int) $validated['rating'];
        $title = mb_substr(trim($validated['review_text']), 0, 60) ?: 'Review';

        // Determine verified purchase by checking delivered orders containing this product for this email
        $deliveredOrderQuery = Order::where('customer_email', $customerEmail)
            ->where('status', 'Delivered')
            ->whereHas('items', function($q) use ($validated) {
                $q->where('product_id', $validated['product_id']);
            });

        // If a specific order code was provided, ensure it matches the delivered order
        if ($orderRecord) {
            $deliveredOrderQuery->where('id', $orderRecord->id);
        }
        // If we have a pattern (grouped order_id), match orders starting with that pattern
        elseif ($orderIdPattern) {
            $deliveredOrderQuery->where(function($q) use ($orderIdPattern) {
                $q->where('order_id', $orderIdPattern)
                  ->orWhere('order_id', 'LIKE', $orderIdPattern . '-%');
            });
        }

        $verifiedPurchase = $deliveredOrderQuery->exists();
        $product = Product::find($validated['product_id']);

        // Fallback: legacy orders table without order_items (match by SKU if product and sku available)
        if (!$verifiedPurchase && $product && $product->sku) {
            // For grouped orders (e.g., ORD-XXX), we need to match both exact and suffixed order_ids (ORD-XXX-2, etc.)
            $baseOrderId = $validated['order_id'] ?? null;
            
            \Log::debug('Review fallback check', [
                'customer_email' => $customerEmail,
                'product_sku' => $product->sku,
                'base_order_id' => $baseOrderId,
            ]);
            
            $fallbackQuery = Order::where('customer_email', $customerEmail)
                ->where('status', 'Delivered')
                ->where('sku', $product->sku);
            
            // Match both exact order_id and suffixed versions (e.g., ORD-XXX and ORD-XXX-2)
            if ($baseOrderId) {
                $fallbackQuery->where(function($q) use ($baseOrderId) {
                    $q->where('order_id', $baseOrderId)
                      ->orWhere('order_id', 'LIKE', $baseOrderId . '-%');
                });
            }
            
            $verifiedPurchase = $fallbackQuery->exists();
            
            \Log::debug('Fallback result', ['verified' => $verifiedPurchase]);
        }

        \Log::debug('Final verification result', [
            'verified_purchase' => $verifiedPurchase,
            'product_id' => $validated['product_id'],
            'customer_email' => $customerEmail,
        ]);

        if (!$verifiedPurchase) {
            return response()->json([
                'success' => false,
                'message' => 'You can only review products that were delivered to you.'
            ], 403);
        }

        $review = Review::create([
            'product_id' => $validated['product_id'],
            'user_id' => null,
            'customer_name' => $customerName ?: 'Customer',
            'customer_email' => $customerEmail,
            'rating' => $rating,
            'title' => $title,
            'review_text' => $validated['review_text'],
            // store numeric order id (FK to orders.id) if found
            'order_id' => $orderRecord?->id,
            'verified_purchase' => $verifiedPurchase,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully! It will be displayed after admin approval.',
            'review' => $review
        ]);
    }

    // Get reviews for a product
    public function getProductReviews($productId)
    {
        $reviews = Review::where('product_id', $productId)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json($reviews);
    }

    // Get review statistics for a product
    public function getProductReviewStats($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $totalReviews = Review::where('product_id', $productId)
            ->where('status', 'approved')
            ->count();

        $averageRating = $product->getAverageRating() ?? 0;

        // Get rating distribution
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Review::where('product_id', $productId)
                ->where('rating', $i)
                ->where('status', 'approved')
                ->count();
            $ratingDistribution[$i] = $count;
        }

        return response()->json([
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 1),
            'rating_distribution' => $ratingDistribution
        ]);
    }

    // Get all reviews (for admin/seller - scoped to their products)
    public function index()
    {
        $seller = $this->currentSeller();
        
        $reviews = Review::with('product', 'user', 'order')
            ->when($seller, function($q) use ($seller) {
                // Only show reviews for this seller's products
                $q->whereHas('product', function($productQuery) use ($seller) {
                    $productQuery->where('seller_id', $seller->id);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('reviews.index', ['reviews' => $reviews]);
    }

    private function currentSeller(): ?User
    {
        $username = session('loginusername');
        return $username ? User::where('name', $username)->first() : null;
    }

    // Approve review (admin)
    public function approve($id)
    {
        $review = Review::find($id);
        if ($review) {
            $review->update(['status' => 'approved']);
            return response()->json(['success' => true, 'message' => 'Review approved']);
        }
        return response()->json(['error' => 'Review not found'], 404);
    }

    // Reject review (admin)
    public function reject($id)
    {
        $review = Review::find($id);
        if ($review) {
            $review->update(['status' => 'rejected']);
            return response()->json(['success' => true, 'message' => 'Review rejected']);
        }
        return response()->json(['error' => 'Review not found'], 404);
    }

    // Delete review
    public function destroy($id)
    {
        $review = Review::find($id);
        if ($review) {
            $review->delete();
            return response()->json(['success' => true, 'message' => 'Review deleted']);
        }
        return response()->json(['error' => 'Review not found'], 404);
    }

    // Mark review as helpful
    public function markHelpful($id)
    {
        $review = Review::find($id);
        if ($review) {
            $review->increment('helpful_count');
            return response()->json(['success' => true, 'helpful_count' => $review->helpful_count]);
        }
        return response()->json(['error' => 'Review not found'], 404);
    }
}
?>
