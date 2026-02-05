<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivityLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

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
            $log->update(['last_activity_at' => now()]);
        }

        cache()->put("user-online:$userId", true, now()->addMinutes(2));
        return response()->json(['status' => 'success']);
    }

    public function ping()
    {
        $userId = auth()->id();
        
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
    public function index(Request $request)
    {
        $query = UserActivityLog::with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $request->status === 'online' 
                ? $query->whereNull('logout_at') 
                : $query->whereNotNull('logout_at');
        }

        if ($request->filled('reason')) {
            $query->where('logout_reason', $request->reason);
        }

        $activities = $query->orderBy('last_activity_at', 'desc')
                            ->paginate(10)
                            ->withQueryString();

        $stats = [
            'total_sessions' => UserActivityLog::whereDate('created_at', now())->count(),
            'avg_duration'   => (int) UserActivityLog::whereDate('created_at', now())
                ->avg(DB::raw('TIMESTAMPDIFF(MINUTE, login_at, last_activity_at)')) ?? 0,
        ];

        return view('admin.activity.index', compact('activities', 'stats'));
    }
}