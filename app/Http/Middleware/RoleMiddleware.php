<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect('login');
        }

        if (!$user->hasAnyRole($roles)) {
            abort(403, 'User does not have the right roles.');
        }

        return $next($request);
    }
}
