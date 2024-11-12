<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class SetAuthGuard
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ?string $guard = null): mixed
    {
        if ($guard) {
            Auth::shouldUse($guard);
        }

        return $next($request);
    }
}
