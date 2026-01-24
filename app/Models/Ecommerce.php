<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ecommerce extends Model
{
    //
    protected $table = 'ecommerce';
    protected $fillable = [
        'currency_symbol',
        'currency_word',
        'store_address',
        'store_city',
        'store_country',
        'store_postal_code',
        'weight_unit',
        'dimension_unit',
    ];

    public function country()
    {
        return $this->belongsTo(Countries::class, 'store_country', 'id');
    }
}
