<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectToUserDashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $firstSegment = $request->segment(1);
        if (!$request->expectsJson() && Auth::guard($guard)->check() && !Auth::guard($guard)->user()->hasPermission('setting_manage') && $firstSegment == 'admin') {
            return redirect(route('user.dashboard'));
        }

        return $next($request);
    }
}
