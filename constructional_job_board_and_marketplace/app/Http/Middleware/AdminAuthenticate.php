<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class AdminAuthenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('/', ['redirect' => $request->getRequestUri()]);
        }
    }


    public function handle($request, Closure $next, ...$guards)
    {
        if (!is_admin()){
            return redirect(route('login', ['redirect' => $request->getRequestUri()]));
        }

        return $next($request);
    }


}
