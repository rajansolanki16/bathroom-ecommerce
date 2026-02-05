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

    $log = UserActivityLog::where('user_id', $userId)
        ->where('session_id', $sessionId)
        ->whereNull('logout_at')
        ->first();

    if (!$log) {
        $log = UserActivityLog::where('user_id', $userId)
            ->where('session_id', $sessionId)
            ->whereNotNull('logout_at')
            ->where('logout_at', '>', now()->subSeconds(30))
            ->first();

        if ($log) {
            $log->update([
                'logout_at' => null,
                'logout_reason' => null,
                'last_activity_at' => now(),
            ]);
        } else {
            UserActivityLog::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'login_at' => now(),
                'last_activity_at' => now(),
            ]);
        }
    } else {
        // Just a standard page navigation update
        $log->update(['last_activity_at' => now()]);
    }

    cache()->put("user-online:$userId", true, now()->addMinutes(2));
    return response()->json(['status' => 'success']);
    }
    public function ping()
    {
        $userId = auth()->id();
        
        // Update the timestamp to keep the session "Live"
        UserActivityLog::where('user_id', $userId)
            ->where('session_id', session()->getId())
            ->whereNull('logout_at')
            ->update(['last_activity_at' => now()]);

        Cache::put("user-online:$userId", true, now()->addMinutes(2));

        return response()->json(['status' => 'pinged']);
    }

    public function offline(Request $request)
    {
        $userId = auth()->id();

        UserActivityLog::where('user_id', $userId)
            ->where('session_id', session()->getId())
            ->whereNull('logout_at')
            ->update([
                'logout_at' => now(),
                'logout_reason' => $request->reason ?? 'closed_tab'
            ]);

        Cache::forget("user-online:$userId");

        return response()->json(['status' => 'offline']);
    }
}