<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\ProductVariant;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;


class Product extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $fillable = [
        'product_title',
        'slug',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'sku_number',

        'product_type',
        'brand_id',
        'short_description',
        'product_decscription',

        'exchangeable',
        'refundable',

        'stock',
        'price',
        'discount',

        'sell_price',
        'sell_price_start_date',
        'sell_price_end_date',

        'weight',
        'length',
        'width',
        'height',

        'free_shipping',

        'status',
        'visibility',

        'product_image',
        'gallery_images',
    ];

    protected $casts = [
        'gallery_images'          => 'array',

        'exchangeable'            => 'boolean',
        'refundable'              => 'boolean',
        'free_shipping'           => 'boolean',

        'sell_price_start_date'   => 'date',
        'sell_price_end_date'     => 'date',

        'price'                   => 'decimal:2',
        'sell_price'              => 'decimal:2',
        'discount'                => 'decimal:2',

        'weight'                  => 'decimal:2',
        'length'                  => 'decimal:2',
        'width'                   => 'decimal:2',
        'height'                  => 'decimal:2',
    ];

    /**
     * Auto-generate SKU if not provided
     */
    protected static function booted()
    {
        static::creating(function ($product) {
            if (empty($product->sku_number)) {
                $product->sku_number = 'SKU-' . strtoupper(Str::random(8));
            }
        });
    }

    /* ============================
       Relationships
    ============================ */

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    // Product → Wishlist (One to Many)
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Product → Users (Many to Many via wishlists)
    public function wishlistedByUsers()
    {
        return $this->belongsToMany(
            User::class,
            'wishlists'
        )->withTimestamps();
    }
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function attributes()
    {
        return $this->belongsToMany(ProductAttribute::class, 'attribute_product', 'product_id', 'product_attribute_id');
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function avgRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_image')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
