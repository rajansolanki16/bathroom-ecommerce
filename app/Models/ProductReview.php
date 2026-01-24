<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'user_id','product_id','order_id','rating','review','is_approved'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}