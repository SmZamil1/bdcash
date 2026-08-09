<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        $admin = auth()->guard('admin')->user();

        if ($admin->id == 1 || $admin->role_id == 0) {
            return $next($request);
        }

        if (!$admin->hasPermission($permission)) {
            $notify[] = ['error', 'You do not have permission to access this module'];
            return back()->withNotify($notify);
        }

        return $next($request);
    }
}
