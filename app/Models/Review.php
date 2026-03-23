<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'product_id',
        'user_id',
        'customer_name',
        'customer_email',
        'rating',
        'title',
        'review_text',
        'order_id',
        'verified_purchase',
        'helpful_count',
        'status'
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Get average rating for a product
    public static function getAverageRating($productId)
    {
        return self::where('product_id', $productId)
            ->where('status', 'approved')
            ->avg('rating');
    }

    // Get rating count for a product
    public static function getRatingCount($productId)
    {
        return self::where('product_id', $productId)
            ->where('status', 'approved')
            ->count();
    }

    // Get reviews by rating
    public static function getReviewsByRating($productId, $rating)
    {
        return self::where('product_id', $productId)
            ->where('rating', $rating)
            ->where('status', 'approved')
            ->get();
    }
}
?>
