<?php

namespace App\Http\Middleware;

use App\ListBuilders\ListBuilder;
use Auth;
use Closure;
use Illuminate\Http\Request;
use View;

class MemberAuthenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! Auth::check() || ! Auth::user()->hasRole('member') || Auth::user()->member->isBlocked()) {
            Auth::logout();

            return redirect()->route('user.register.create');

        } else {
            View::share('lastLoginLog', Auth::user()->member->loginLogs()->orderBy('created_at', 'desc')
                ->skip(1)
                ->take(1)
                ->first());

            ListBuilder::$viewPrefix = 'member';

            return $next($request);
        }
    }
}
