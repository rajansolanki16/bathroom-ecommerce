<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreVisit extends Model
{
    protected $fillable = [
        'salesman_id', 'vendor_id', 'purpose', 'notes', 
        'feedback', 'rating', 'outcome', 
        'follow_up_required', 'next_follow_up_date','location_address'
    ];

    public function salesman() {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function vendor() {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}