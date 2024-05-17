<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\Role;

class Dashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        //if (!Auth::check() or !Auth::user()->hasPermission('dashboard_access')) {
            //return redirect('/');
        //}
        // FIX: when session expired, throw exception on header user property access on null
        if(!Auth::check() || !Auth::user()->role_id === Role::ADMIN) {
            return redirect('/');
        }

        return $next($request);
    }
}
