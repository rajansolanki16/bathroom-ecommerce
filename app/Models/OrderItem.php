<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id','product_id','price','quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function hasPurchased($productId)
    {
        if (!Auth::check()) {
            return false;
        }

        return self::where('product_id', $productId)
            ->whereHas('order', function ($q) {
                $q->where('user_id', Auth::id())
                  ->where('status', 'completed');
            })
            ->exists();
    }
}