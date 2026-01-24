<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbandonedCart extends Model
{
    use HasFactory;
    protected $table = 'abandoned_cart';

    protected $primaryKey = 'id';
    
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'check_in',
        'check_out',
        'adults',
        'children',
        'room_id',
        'room_count',
        'extra_beds',
        'services',
        'total_cost',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'services' => 'array',
    ];
}
