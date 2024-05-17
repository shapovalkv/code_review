<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserProject;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class ProjectSelectionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::user()->hasRole(User::ROLE_CUSTOMER) && (empty(Auth::user()->selected_project_id) || empty(UserProject::find(Auth::user()->selected_project_id)))) {
            return redirect(route('createProject'));
        }

        return $next($request);
    }
}
