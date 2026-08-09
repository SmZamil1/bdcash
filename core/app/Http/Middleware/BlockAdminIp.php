<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\AdminIpBan;

class BlockAdminIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = getRealIP();
        $isBanned = AdminIpBan::where('ip', $ip)->exists();

        if ($isBanned) {
            abort(403, 'Your IP address has been banned by the administrator.');
        }

        return $next($request);
    }
}
