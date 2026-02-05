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

    // For the Admin Panel: Is the user active right now?
    public function getIsLiveAttribute()
    {
        // If last ping was less than 2 mins ago and they haven't "closed" the tab
        return $this->last_activity_at > now()->subMinutes(2) && is_null($this->logout_at);
    }

    // For the Admin Panel: How long was this specific session?
    public function getDurationMinutesAttribute()
    {
        return $this->login_at->diffInMinutes($this->last_activity_at);
    }
}

