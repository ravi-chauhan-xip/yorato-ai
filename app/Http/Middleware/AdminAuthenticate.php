<?php

namespace App\Http\Middleware;

use App\ListBuilders\ListBuilder;
use App\Models\Admin;
use Auth;
use Closure;
use View;

class AdminAuthenticate
{
    public function handle($request, Closure $next): mixed
    {
        $user = Auth::user();

        if ($user instanceof Admin) {
            if (Auth::user()->status == Admin::STATUS_IN_ACTIVE) {
                Auth::logout();

                return redirect()->route('admin.login.create')->with(['error' => 'Your account is inactive']);
            } else {
                View::share('lastLoginLog', Auth::user()->adminLoginLogs()->orderBy('created_at', 'desc')->skip(1)->take(1)->first());

                ListBuilder::$viewPrefix = 'admin';

                return $next($request);
            }
        }

        return redirect()->route('admin.login.create');
    }
}
