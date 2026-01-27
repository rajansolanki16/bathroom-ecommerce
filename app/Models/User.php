<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'mobile',
        'whatsapp_number',
        'state',
        'area',
        'address',
        'country',
        'password',
        'email_verified_at',
        'is_active',
        'is_approved',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    // User → Wishlist (One to Many)
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // User → Products (Many to Many via wishlists table)
    public function wishlistProducts()
    {
        return $this->belongsToMany(
            Product::class,
            'wishlists'
        )->withTimestamps();
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
