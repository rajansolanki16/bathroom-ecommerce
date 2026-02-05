<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'login_at',
        'last_activity_at',
        'logout_at',
        'logout_reason',
    ];

    protected $appends = ['is_live', 'duration_minutes'];

    protected $casts = [
        'login_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'logout_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getIsLiveAttribute()
    {
        return $this->last_activity_at > now()->subMinutes(2) && is_null($this->logout_at);
    }

    public function getDurationMinutesAttribute()
    {
        return $this->login_at->diffInMinutes($this->last_activity_at);
    }
}

