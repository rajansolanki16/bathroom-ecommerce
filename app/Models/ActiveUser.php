<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ActiveUser extends Model
{
    protected $fillable = ['user_id', 'last_active_at'];
    public $timestamps = false;

    public static function markActive($userId)
    {
        static::updateOrCreate(
            ['user_id' => $userId],
            ['last_active_at' => now()]
        );
    }

    public static function getActive($minutes = 10)
    {
        return static::where('last_active_at', '>=', now()->subMinutes($minutes))->with('user')->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
