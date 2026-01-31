<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Brand extends Model  implements HasMedia
{
    use HasFactory;
     use InteractsWithMedia;

    protected $table = 'brands';

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'description',
        'is_active',
        'media_library_logo_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Auto generate slug if not provided
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });

        static::updating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name);
            }
        });
    }
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('brand_logo')->singleFile();
    }

    public function getLogoUrlAttribute()
    {
        return $this->hasMedia('brand_logo')
            ? $this->getFirstMediaUrl('brand_logo')
            : asset('admin/images/no-image.png');
    }
}
