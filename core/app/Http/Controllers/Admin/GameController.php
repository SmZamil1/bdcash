<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameSetting;
use App\Models\CrashGame;
use App\Models\AviatorUpcomingResult;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $pageTitle = 'Games Management';
        $games = GameSetting::all();
        return view('admin.games.index', compact('pageTitle', 'games'));
    }

    public function edit($id)
    {
        $game = GameSetting::findOrFail($id);
        $pageTitle = 'Edit Game: ' . $game->name;
        return view('admin.games.edit', compact('pageTitle', 'game'));
    }

    public function update(Request $request, $id)
    {
        $game = GameSetting::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'win_chance' => 'required|numeric|min:0|max:10000',
            'min_bet' => 'required|numeric|min:0',
            'max_bet' => 'required|numeric|min:0',
            'max_multiplier' => 'nullable|numeric|min:1',
            'bet_options' => 'required|string',
            'status' => 'nullable|in:0,1,on',
            'image' => 'nullable|image|mimes:jpg,jpeg,png'
        ]);

        $game->name = $request->name;
        $game->win_chance = $request->win_chance;
        $game->min_bet = $request->min_bet;
        $game->max_bet = $request->max_bet;
        $game->max_multiplier = $request->max_multiplier ?? 500;
        $game->bet_options = $request->bet_options;
        $game->status = $request->status ? 1 : 0;

        if ($request->hasFile('image')) {
            try {
                $path = 'assets/games';
                $filename = fileUploader($request->image, $path, null, $game->image);
                $game->image = $filename;
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $game->save();

        $notify[] = ['success', 'Game setting updated successfully'];
        return back()->withNotify($notify);
    }


    public function aviatorLogs()
    {
        $pageTitle = 'Aviator Game Logs';
        $logs = CrashGame::orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.games.aviator_logs', compact('pageTitle', 'logs'));
    }
    public function aviatorDashboard()
    {
        $pageTitle = 'Aviator Live Dashboard';
        return view('admin.games.aviator_dashboard', compact('pageTitle'));
    }

    public function aviatorPoll()
    {
        // Ensure we have upcoming results
        $this->generateUpcomingResults();

        // Fix: Filter active bets to relatively recent ones (last 5 minutes) 
        // to prevent stuck/old "Active" bets from cluttering the dashboard.
        $activeBets = \App\Models\CrashGame::where('status', 0)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->with('user')
            ->orderBy('id', 'desc')
            ->get()
            ->unique('id'); // Ensure uniqueness
        
        $bets = $activeBets->map(function($bet) {
            $elapsedSeconds = now()->floatDiffInSeconds($bet->created_at);
            $estimatedMultiplier = 1.00 + ($elapsedSeconds * 0.1);
            if($estimatedMultiplier > $bet->crash_point) $estimatedMultiplier = $bet->crash_point;

            return [
                'username' => $bet->user->username,
                'bet_amount' => $bet->bet_amount,
                'multiplier' => number_format($estimatedMultiplier, 2),
                'payout' => number_format($bet->bet_amount * $estimatedMultiplier, 2),
                'status' => 'Active',
                'created_at' => $bet->created_at->format('H:i:s')
            ];
        });

        $history = \App\Models\CrashGame::where('status', '!=', 0)
            ->with('user')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get()
            ->map(function($h) {
                return [
                    'username' => $h->user->username,
                    'bet_amount' => $h->bet_amount,
                    'multiplier' => $h->status == 1 ? $h->win_amount / $h->bet_amount : 0,
                    'crash_point' => $h->crash_point,
                    'payout' => $h->win_amount,
                    'status' => $h->status == 1 ? 'Won' : 'Lost',
                    'created_at' => $h->created_at->format('H:i:s')
                ];
            });

        $upcoming = AviatorUpcomingResult::where('is_used', false)->take(10)->get();
        
        // Aggregated Stats
        $totalBetsCount = CrashGame::count();
        $totalWagered = CrashGame::sum('bet_amount');
        $totalPaid = CrashGame::where('status', 1)->sum('win_amount');
        $adminProfit = $totalWagered - $totalPaid;

        return response()->json([
            'active_bets_count' => $activeBets->count(),
            'total_bet_amount' => $activeBets->sum('bet_amount'), // Running bets
            'bets' => $bets->values(), // Reset keys after unique()
            'history' => $history,
            'upcoming' => $upcoming,
            'stats' => [ // New Stats Block
                'total_bets' => $totalBetsCount,
                'total_wagered' => $totalWagered,
                'total_paid' => $totalPaid, // User Profit / Admin Loss
                'admin_profit' => $adminProfit, // Net Admin Profit
                'admin_loss' => $totalPaid // Explicit field for Admin Loss as requested
            ]
        ]);
    }

    private function generateUpcomingResults()
    {
        $count = AviatorUpcomingResult::where('is_used', false)->count();
        if ($count < 20) {
            $setting = GameSetting::where('game_key', 'aviator')->first();
            $maxMult = $setting ? (float)$setting->max_multiplier : 500.00;
            
            for ($i = 0; $i < (20 - $count); $i++) {
                if (mt_rand(1, 100) <= 10) {
                    $crashPoint = 1.00;
                } else {
                    $r = mt_rand(1, 1000) / 1000;
                    $crashPoint = 1.01 + ($r * ($maxMult - 1.01));
                    $crashPoint = floor($crashPoint * 100) / 100;
                }
                AviatorUpcomingResult::create(['crash_point' => $crashPoint]);
            }
        }
    }
}
