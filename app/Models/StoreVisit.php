<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreVisit extends Model
{
    protected $fillable = [
        'salesman_id',
        'vendor_id',
        'purpose',
        'notes',
        'feedback',
        'rating',
        'outcome',
        'follow_up_required',
        'next_follow_up_date',
        'location_address',
        'is_approve',
        'status',
        'reject_reason',
        'approved_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'next_follow_up_date' => 'datetime', 
        'follow_up_required' => 'boolean',
    ];


    public function salesman()
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
