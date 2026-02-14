<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    //
    protected $fillable = ['name', 'slug',     'show_on_home'];

    protected $casts = [
        'show_on_home' => 'boolean',
    ];
}
