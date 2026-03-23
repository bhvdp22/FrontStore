<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductReturn;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class SellerReputationService
{
    private const WEIGHT_FULFILLMENT = 25;
    private const WEIGHT_DISPATCH = 20;
    private const WEIGHT_RETURN_RATE = 20;
    private const WEIGHT_REVIEW = 20;
    private const WEIGHT_RESPONSE = 15;

    private const FULFILLED_STATUSES = ['Delivered', 'Shipped', 'Completed', 'delivered', 'shipped', 'completed'];

    /**
     * Calculate reputation data for a single seller.
     */
    public function calculate(int $sellerId): array
    {
        $orderQuery = Order::query()->where('seller_id', $sellerId);
        $totalOrders = (int) $orderQuery->count();

        $fulfilledOrders = (int) Order::query()
            ->where('seller_id', $sellerId)
            ->whereIn('status', self::FULFILLED_STATUSES)
            ->count();

        $fulfillmentRate = $totalOrders > 0
            ? round(($fulfilledOrders / $totalOrders) * 100, 2)
            : null;
        $fulfillmentScore = $fulfillmentRate !== null ? $fulfillmentRate : 70;

        $dispatchHours = $this->getAverageDispatchHours($sellerId);
        $dispatchScore = $this->scoreDispatchHours($dispatchHours);

        $totalReturns = (int) ProductReturn::query()
            ->where('seller_id', $sellerId)
            ->count();

        $returnRate = $totalOrders > 0
            ? round(($totalReturns / $totalOrders) * 100, 2)
            : null;
        $returnRateScore = $returnRate !== null
            ? max(0, round(100 - ($returnRate * 4), 2))
            : 70;

        $reviewStats = Review::query()
            ->join('products', 'products.id', '=', 'reviews.product_id')
            ->where('products.seller_id', $sellerId)
            ->where('reviews.status', 'approved')
            ->selectRaw('AVG(reviews.rating) as avg_rating, COUNT(reviews.id) as review_count')
            ->first();

        $reviewCount = (int) ($reviewStats->review_count ?? 0);
        $avgRating = $reviewCount > 0 ? round((float) $reviewStats->avg_rating, 2) : null;
        $reviewScore = $avgRating !== null ? round(($avgRating / 5) * 100, 2) : 70;

        $responseHours = $this->getAverageReturnResponseHours($sellerId);
        $responseScore = $this->scoreResponseHours($responseHours);

        $weightedScore =
            (($fulfillmentScore * self::WEIGHT_FULFILLMENT) +
            ($dispatchScore * self::WEIGHT_DISPATCH) +
            ($returnRateScore * self::WEIGHT_RETURN_RATE) +
            ($reviewScore * self::WEIGHT_REVIEW) +
            ($responseScore * self::WEIGHT_RESPONSE)) / 100;

        $score = (int) round(max(0, min(100, $weightedScore)));

        $hasEnoughData = $totalOrders >= 5 || $reviewCount >= 3 || $totalReturns >= 3;
        $badge = $this->resolveBadge($score, $hasEnoughData);

        return [
            'score' => $score,
            'badge' => $badge,
            'breakdown' => [
                'orders_total' => $totalOrders,
                'orders_fulfilled' => $fulfilledOrders,
                'fulfillment_rate' => $fulfillmentRate,
                'avg_dispatch_hours' => $dispatchHours,
                'returns_total' => $totalReturns,
                'return_rate' => $returnRate,
                'avg_rating' => $avgRating,
                'review_count' => $reviewCount,
                'avg_return_response_hours' => $responseHours,
                'component_scores' => [
                    'fulfillment' => round($fulfillmentScore, 2),
                    'dispatch' => round($dispatchScore, 2),
                    'return_rate' => round($returnRateScore, 2),
                    'reviews' => round($reviewScore, 2),
                    'response' => round($responseScore, 2),
                ],
                'weights' => [
                    'fulfillment' => self::WEIGHT_FULFILLMENT,
                    'dispatch' => self::WEIGHT_DISPATCH,
                    'return_rate' => self::WEIGHT_RETURN_RATE,
                    'reviews' => self::WEIGHT_REVIEW,
                    'response' => self::WEIGHT_RESPONSE,
                ],
            ],
            'calculated_at' => now(),
        ];
    }

    /**
     * Persist latest reputation data for a seller.
     */
    public function persistForSeller(User $seller): array
    {
        $data = $this->calculate((int) $seller->id);

        $seller->forceFill([
            'seller_reputation_score' => $data['score'],
            'seller_reputation_badge' => $data['badge'],
            'seller_reputation_breakdown' => $data['breakdown'],
            'seller_reputation_calculated_at' => $data['calculated_at'],
        ])->save();

        return $data;
    }

    /**
     * Populate seller reputation on loaded product collections without changing existing flows.
     */
    public function attachReputationToProducts(iterable $products): void
    {
        $sellerIds = collect($products)
            ->map(fn ($product) => $product->seller_id ?? null)
            ->filter()
            ->unique()
            ->values();

        if ($sellerIds->isEmpty()) {
            return;
        }

        $results = [];

        foreach ($sellerIds as $sellerId) {
            $results[(int) $sellerId] = $this->calculate((int) $sellerId);
        }

        foreach ($products as $product) {
            if (!$product->seller_id || !$product->seller) {
                continue;
            }

            $sellerData = $results[(int) $product->seller_id] ?? null;
            if (!$sellerData) {
                continue;
            }

            $product->seller->setAttribute('seller_reputation_score', $sellerData['score']);
            $product->seller->setAttribute('seller_reputation_badge', $sellerData['badge']);
            $product->seller->setAttribute('seller_reputation_breakdown', $sellerData['breakdown']);
            $product->seller->setAttribute('seller_reputation_calculated_at', $sellerData['calculated_at']);
        }
    }

    private function getAverageDispatchHours(int $sellerId): ?float
    {
        $orders = Order::query()
            ->where('seller_id', $sellerId)
            ->whereIn('status', self::FULFILLED_STATUSES)
            ->select(['created_at', 'updated_at'])
            ->get();

        if ($orders->isEmpty()) {
            return null;
        }

        $hours = $orders
            ->map(function ($order) {
                if (!$order->created_at || !$order->updated_at) {
                    return null;
                }
                return max(0, Carbon::parse($order->created_at)->diffInHours(Carbon::parse($order->updated_at)));
            })
            ->filter(fn ($value) => $value !== null)
            ->values();

        if ($hours->isEmpty()) {
            return null;
        }

        return round($hours->avg(), 2);
    }

    private function getAverageReturnResponseHours(int $sellerId): ?float
    {
        $returns = ProductReturn::query()
            ->where('seller_id', $sellerId)
            ->where(function ($query) {
                $query->whereNotNull('approved_at')
                    ->orWhereNotNull('rejected_at');
            })
            ->select(['created_at', 'approved_at', 'rejected_at'])
            ->get();

        if ($returns->isEmpty()) {
            return null;
        }

        $hours = $returns
            ->map(function ($returnItem) {
                if (!$returnItem->created_at) {
                    return null;
                }

                $respondedAt = $returnItem->approved_at ?: $returnItem->rejected_at;
                if (!$respondedAt) {
                    return null;
                }

                return max(0, Carbon::parse($returnItem->created_at)->diffInHours(Carbon::parse($respondedAt)));
            })
            ->filter(fn ($value) => $value !== null)
            ->values();

        if ($hours->isEmpty()) {
            return null;
        }

        return round($hours->avg(), 2);
    }

    private function scoreDispatchHours(?float $hours): float
    {
        if ($hours === null) {
            return 70;
        }

        return match (true) {
            $hours <= 24 => 100,
            $hours <= 48 => 90,
            $hours <= 72 => 75,
            $hours <= 120 => 60,
            default => 40,
        };
    }

    private function scoreResponseHours(?float $hours): float
    {
        if ($hours === null) {
            return 70;
        }

        return match (true) {
            $hours <= 12 => 100,
            $hours <= 24 => 90,
            $hours <= 48 => 75,
            $hours <= 72 => 60,
            default => 40,
        };
    }

    private function resolveBadge(int $score, bool $hasEnoughData): string
    {
        if (!$hasEnoughData) {
            return 'New Seller';
        }

        return match (true) {
            $score >= 90 => 'Trusted Seller',
            $score >= 75 => 'Reliable',
            $score >= 60 => 'Growing Seller',
            default => 'Needs Attention',
        };
    }
}
