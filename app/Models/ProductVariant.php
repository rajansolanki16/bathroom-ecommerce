<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\AttributeValue;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProductVariant extends Model implements HasMedia
{
    use InteractsWithMedia ;
    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'sku',
        'price',
        'stock',
        'sell_price',
        'shipping',
        'image',
        'weight',
        'length',
        'width',
        'height',
        'status',
        'visibility',
        'exchangeable',
        'refundable',
        'free_shipping',
        'shipping_address',
        'general_info',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sell_price' => 'decimal:2',
        'stock' => 'integer',
        'exchangeable' => 'boolean',
        'refundable' => 'boolean',
        'free_shipping' => 'boolean',
        'general_info' => 'string',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function attributeValues()
    {
        if (Schema::hasTable('variant_attribute_values')) {
            return $this->belongsToMany(AttributeValue::class, 'variant_attribute_values', 'product_variant_id', 'attribute_value_id');
        }

        return $this->belongsToMany(AttributeValue::class, 'product_variant_attribute_value', 'product_variant_id', 'attribute_value_id');
    }
}
