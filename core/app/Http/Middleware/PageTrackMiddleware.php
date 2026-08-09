<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PageVisit;
use Illuminate\Support\Facades\Auth;

class PageTrackMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ignore API, Admin, and Assets
        if ($request->is('api/*') || $request->is('admin/*') || $request->is('assets/*') || $request->is('ipn/*')) {
            return $next($request);
        }

        // Ignore AJAX requests usually
        if ($request->ajax()) {
            return $next($request);
        }

        try {
            PageVisit::create([
                'ip_address' => $request->ip(),
                'url'        => substr($request->fullUrl(), 0, 255),
                'user_agent' => substr($request->userAgent(), 0, 255),
                'user_id'    => Auth::id(),
                'session_id' => session()->getId(),
            ]);
        } catch (\Exception $e) {
            // Fail silently to not disrupt the user
        }

        return $next($request);
    }
}
