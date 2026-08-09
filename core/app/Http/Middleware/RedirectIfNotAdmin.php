<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (!Auth::guard($guard)->check()) {
            return to_route('admin.login');
        }

        $user = Auth::guard($guard)->user();
        if ($user->is_secondary_auth_enabled) {
            $exemptRoutes = [
                'admin.security.verify', 
                'admin.security.verify.post', 
                'admin.logout',
                'admin.login',
            ];
            
            if (!session('admin_secondary_auth_verified') && !in_array(request()->route()->getName(), $exemptRoutes)) {
                return to_route('admin.security.verify');
            }
        }

        return $next($request);
    }
}
