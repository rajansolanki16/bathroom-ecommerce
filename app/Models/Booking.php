<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bookings';

    protected $primaryKey = 'id';
    
    protected $keyType = 'int';

    protected $fillable = [
        'type',
        'user_id',
        'check_in',
        'check_out',
        'adults',
        'children',
        'room_count',
        'room_id',
        'extra_beds',
        'services',
        'total_cost',
        'transaction_id',
        'customer_note',
        'customer_details',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'services' => 'array',
        'customer_details' => 'array',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'transaction_id');
    }
}
