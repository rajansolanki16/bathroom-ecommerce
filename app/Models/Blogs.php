<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blogs extends Model
{
    use HasFactory;
    protected $table = 'blogs';
    public function categories()
    {
        return $this->belongsToMany(BlogCategories::class, 'blog_relation_with_categories', 'blogs_id', 'blog_categories_id');
    }
}
