<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WishList extends Model
{
    //
    protected $table = "wishlists";
    protected $appends = ['is_wishlisted'];
    protected $fillable = [
        'user_id',
        'product_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getIsWishlistedAttribute(): bool
    {
        if (!Auth::check()) {
            return false;
        }

        return $this->wishlists()
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function wishlists()
    {
    return $this->hasMany(WishList::class);
    }
}
