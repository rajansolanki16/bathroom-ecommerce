<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;
use App\Models\OrderAudit;


class Order extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'total',
        'status',
        'internal_notes',
    ];


    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function audits()
    {
        return $this->hasMany(OrderAudit::class);
    }
}
