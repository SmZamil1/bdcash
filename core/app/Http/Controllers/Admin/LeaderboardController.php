<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Invest;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function index()
    {
        $pageTitle = 'Leaderboard';

        // Top 10 Investors
        $topInvestors = Invest::with('user')
            ->selectRaw('user_id, sum(amount) as total_amount')
            ->groupBy('user_id')
            ->orderBy('total_amount', 'desc')
            ->take(10)
            ->get();

        // Top 10 Depositors
        $topDepositors = Deposit::successful()
            ->with('user')
            ->selectRaw('user_id, sum(amount) as total_amount')
            ->groupBy('user_id')
            ->orderBy('total_amount', 'desc')
            ->take(10)
            ->get();

        // Top 10 Withdrawers
        $topWithdrawers = Withdrawal::approved()
            ->with('user')
            ->selectRaw('user_id, sum(amount) as total_amount')
            ->groupBy('user_id')
            ->orderBy('total_amount', 'desc')
            ->take(10)
            ->get();

        return view('admin.leaderboard', compact('pageTitle', 'topInvestors', 'topDepositors', 'topWithdrawers'));
    }
}
