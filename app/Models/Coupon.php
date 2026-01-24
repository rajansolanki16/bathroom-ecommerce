<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    //

    protected $table = 'coupons';
    protected $fillable = [
        'code', 'description', 'type', 'amount',
        'start_date', 'expiry_date', 'max_usage', 'used'
    ];

}
