<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()->hasRole('marketplace-user')) {
            return redirect()->route('user.marketplace_user.index');
        } elseif ($request->user()->hasRole('candidate')) {
            return redirect()->route('user.candidate.index');
        } elseif ($request->user()->hasRole('employer')) {
            return redirect()->route('user.company.profile');
        }

        return redirect()->route('home');
    }
}
