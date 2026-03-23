<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * @property int $id
 * @property string $name
 * @property string $sku
 * @property string|null $asin
 * @property float $price
 * @property int $quantity
 * @property string $status
 * @property string|null $description
 * @property string|null $img_path
 * @property bool $is_sponsored
 * @property int|null $seller_id
 * @property int|null $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string|null $image
 * @property-read array $all_images
 */
class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name', 
        'sku', 
        'asin', 
        'price', 
        'quantity', 
        'status', 
        'description', 
        'img_path', 
        'is_sponsored', 
        'seller_id',
        'category_id',
    ];

    protected $casts = [
        'is_sponsored' => 'boolean',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'sku', 'sku');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Multiple product images
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    /**
     * Get primary image (first image or fallback to img_path)
     */
    public function getImageAttribute()
    {
        $primary = $this->images()->where('is_primary', true)->first();
        if ($primary) {
            return $primary->image_path;
        }
        $first = $this->images()->first();
        if ($first) {
            return $first->image_path;
        }
        return $this->img_path;
    }

    /**
     * Get all image URLs for gallery display
     */
    public function getAllImagesAttribute()
    {
        $images = $this->images()->get();
        if ($images->count() > 0) {
            return $images->pluck('image_path')->toArray();
        }
        // Fallback to single img_path
        if ($this->img_path) {
            return [$this->img_path];
        }
        return [];
    }

    public function getAverageRating()
    {
        return Review::getAverageRating($this->id);
    }

    public function getReviewCount()
    {
        return Review::getRatingCount($this->id);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
