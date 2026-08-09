<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageVisit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $pageTitle = 'Analytics Dashboard';

        // Summary Cards
        $totalVisits = PageVisit::count();
        $uniqueVisitors = PageVisit::distinct('ip_address')->count();
        $todayVisits = PageVisit::whereDate('created_at', Carbon::today())->count();
        // Online users based on last 5 minutes activity (approx)
        $onlineUsers = PageVisit::where('created_at', '>=', Carbon::now()->subMinutes(5))
            ->distinct('ip_address')
            ->count();

        // Top Pages
        // Group by URL, limit result
        $topPages = PageVisit::select('url', DB::raw('count(*) as count'))
            ->groupBy('url')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        // Recent Activity
        $recentLogs = PageVisit::latest()->with('user')->limit(20)->get();

        // Browsers Chart Data
        $browsers = PageVisit::select('user_agent', DB::raw('count(*) as count'))
             ->groupBy('user_agent')
             ->get()
             ->groupBy(function($item) {
                 $agent = $item->user_agent;
                 if (strpos($agent, 'Chrome') !== false && strpos($agent, 'Edge') === false) return 'Chrome';
                 if (strpos($agent, 'Firefox') !== false) return 'Firefox';
                 if (strpos($agent, 'Safari') !== false && strpos($agent, 'Chrome') === false) return 'Safari';
                 if (strpos($agent, 'Edge') !== false) return 'Edge';
                 if (strpos($agent, 'Opera') !== false) return 'Opera';
                 return 'Others';
             })
             ->map(function($group) {
                 return $group->sum('count');
             });

        $emptyMessage = 'No data found';

        return view('admin.analytics.index', compact('pageTitle', 'totalVisits', 'uniqueVisitors', 'todayVisits', 'onlineUsers', 'topPages', 'recentLogs', 'browsers', 'emptyMessage'));
    }
}
