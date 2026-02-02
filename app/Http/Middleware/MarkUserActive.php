<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActiveUser;
//log use 
use Illuminate\Support\Facades\Log;

class MarkUserActive
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('MarkUserActive middleware triggered');
        if (Auth::check()) {
            Log::info('MarkUserActive: ' . Auth::id());
            ActiveUser::markActive(Auth::id());
        }
        return $next($request);
    }
}
