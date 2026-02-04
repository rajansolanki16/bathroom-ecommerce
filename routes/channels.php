<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

Broadcast::channel('presence-online', function ($user) {
    return [
        'id' => $user->id,
        'name' => $user->name,
    ];
});
