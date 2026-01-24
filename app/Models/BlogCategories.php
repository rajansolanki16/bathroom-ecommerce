<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategories extends Model
{
    use HasFactory;
    protected $table = 'blog_categories';

    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'name',
    ];
    public function blogs()
    {
        return $this->belongsToMany(Blogs::class, 'blog_relation_with_categories', 'blog_categories_id', 'blogs_id');
    }
}
