<?php

namespace App\Http\Middleware\Role;

use Closure;
use Illuminate\Http\Request;

class EnsureRoleIsSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if ($user->hasRole('super admin')) {
            return $next($request);
        } else {
            return response()->json('You are not allowed!(admin role)', 403);
        }
    }
}
