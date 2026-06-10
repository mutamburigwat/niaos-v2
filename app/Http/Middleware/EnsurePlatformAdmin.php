<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsurePlatformAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isPlatformAdmin()) {
            abort(403, 'Access denied. Platform admin privileges required.');
        }

        return $next($request);
    }
}
