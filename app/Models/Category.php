<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = [
        'name',
        'slug',
        'parent_id',
        'is_visible'
    ];

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }


}
