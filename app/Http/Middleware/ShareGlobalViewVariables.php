<?php

namespace App\Http\Middleware;

use App\Models\WebSetting;
use Closure;
use View;

class ShareGlobalViewVariables
{
    public function handle($request, Closure $next): mixed
    {
        //        View::share('webSettings', WebSetting::firstOrNew([]));

        return $next($request);
    }
}
