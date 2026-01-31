<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $table = 'settings';

    protected $primaryKey = 'id';
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'page',
        'slug',
        'type',
        'value',
    ];

    // Optionally, you can define media collections here
    // public function registerMediaCollections(): void
    // {
    //     $this->addMediaCollection('uploads');
    // }

    public function registerMediaConversions(?\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->sharpen(10);
        $this->addMediaConversion('medium')
            ->width(400)
            ->height(400);
        $this->addMediaConversion('large')
            ->width(1200)
            ->height(1200);
    }

    public function registerMediaCollections(): void
{
    $this->addMediaCollection('brand')->singleFile();
}
}
