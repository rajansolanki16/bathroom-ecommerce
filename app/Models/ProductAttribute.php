<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $table = 'product_attribute';

    protected $fillable = [
        'name',
        'slug',
    ];

    // Products using this attribute
    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'attribute_product',
            'product_attribute_id',
            'product_id'
        );
    }

    // Attribute values (Color → Red, Blue)
    public function values()
    {
        return $this->hasMany(AttributeValue::class, 'product_attribute_id');
    }
    
    
}
