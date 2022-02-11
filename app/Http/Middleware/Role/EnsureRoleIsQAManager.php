<?php

namespace App\Http\Middleware\Role;

use Closure;
use Illuminate\Http\Request;

class EnsureRoleIsQAManager
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
        $user = $request->user();

        if ($user->hasRole('quality assurance manager')) {
            return $next($request);
        } else {
            return response()->json('You are not allowed! (quality assurance manager)', 403);
        }
    }
}
