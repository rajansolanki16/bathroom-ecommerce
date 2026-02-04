<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Cache;

class UserActivityController extends Controller
{
public function online()
{
    $userId = auth()->id();
    $sessionId = session()->getId();

    if (!$userId) return response()->json(['error' => 'Unauthenticated'], 401);

    // firstOrCreate uses the first array to FIND, and second array to CREATE
    UserActivityLog::updateOrCreate(
        [
            'user_id' => $userId,
            'session_id' => $sessionId,
            'logout_at' => null,
        ],
        [
            'login_at' => now(),
            'last_activity_at' => now(),
        ]
    );

    cache()->put("user-online:$userId", true, now()->addMinutes(2));
    
    return response()->json(['status' => 'success']);
}

    public function offline(Request $request)
    {
        $userId = auth()->id();

        UserActivityLog::where('user_id', $userId)
            ->where('session_id', session()->getId())
            ->whereNull('logout_at')
            ->update([
                'logout_at' => now(),
                'last_activity_at' => now(),
                'logout_reason' => $request->reason ?? 'unknown',
            ]);

        cache()->forget("user-online:$userId");
    }

    public function ping()
    {
        $userId = auth()->id();

        cache()->put("user-online:$userId", true, now()->addMinutes(2));

        UserActivityLog::where('user_id', $userId)
            ->where('session_id', session()->getId())
            ->whereNull('logout_at')
            ->update([
                'last_activity_at' => now(),
            ]);
    }
}
