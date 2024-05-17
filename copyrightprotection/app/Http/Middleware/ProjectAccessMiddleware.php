<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserProject;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        $project = $request->route('project', UserProject::find($user->selected_project_id));

        if ($user->hasRole(User::ROLE_AGENT)) {
            if (!$user->AgentAssignedToProject($project)) {
                return abort(403);
            }
        } else if ($user->hasRole(User::ROLE_CUSTOMER)) {
            if (!$user->CustomerHasProject($project)) {
                return redirect(route('createProject'));
            }
        } else if ($user->hasRole(User::ROLE_ADMIN)) {
            return $next($request);
        } else {
            return abort(403);
        }

        return $next($request);
    }
}

