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
}

