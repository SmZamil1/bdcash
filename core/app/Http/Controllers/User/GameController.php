<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\GameSetting;
use App\Models\CrashGame;
use App\Models\AviatorUpcomingResult;
use App\Models\AviatorRound;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\GatewayCurrency;
use App\Constants\Status;

class GameController extends Controller
{
    public function index()
    {
        $pageTitle = "Games Hub";
        $games = GameSetting::where('status', 1)->get();
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();
        return view(activeTemplate() . 'user.games.index', compact('pageTitle', 'games', 'gatewayCurrency'));
    }

    public function fortuneMaya()
    {
        $pageTitle = "Fortune Maya";
        $user = Auth::user();
        $setting = GameSetting::where('game_key', 'fortune-maya')->firstOrFail();
        return view(activeTemplate() . 'user.games.fortune_maya', compact('pageTitle', 'user', 'setting'));
    }





    public function gameBalance()
    {
        return response()->json(['balance' => Auth::user()->interest_wallet]);
    }

    public function gameSpin(Request $request)
    {
        $request->validate([
            'bet' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $bet = $request->bet;
        $setting = GameSetting::where('game_key', 'fortune-maya')->first();

        if ($user->interest_wallet < $bet) {
            return response()->json(['error' => 'Insufficient balance', 'redirect' => route('user.deposit.index')], 400);
        }
        
        if ($setting) {
            if ($bet < $setting->min_bet || $bet > $setting->max_bet) {
                return response()->json(['error' => 'Invalid bet amount'], 400);
            }
        }

        // Deduct Bet
        $user->interest_wallet -= $bet;
        $user->save();

        // Transaction for Bet
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $bet;
        $transaction->post_balance = $user->interest_wallet;
        $transaction->charge = 0;
        $transaction->trx_type = '-';
        $transaction->details = 'Bet in Fortune Maya';
        $transaction->trx = $trx;
        $transaction->remark = 'game_bet';
        $transaction->save();
        
        // Game Logic
        $symbols = [
            "icon1", "icon2", "icon3", "icon4", "icon5",
            "icon6", "icon7", "icon8", "icon9", "wild"
        ];
        $rows = 3;
        $cols = 3;
        $grid = [];
        
        // Win Configuration
        $forceWinChance = ($setting->win_chance ?? 30) / 100;
        $isForcedWin = (mt_rand(1, 100) / 100) < $forceWinChance;
        $forcedLine = mt_rand(0, $rows - 1);
        // Pick a forced symbol (not wild)
        $forcedSymbol = $symbols[mt_rand(0, 8)]; 

        // Generate Grid
        for ($c = 0; $c < $cols; $c++) {
            for ($r = 0; $r < $rows; $r++) {
                if ($isForcedWin && $r == $forcedLine) {
                    // 25% chance of Wild in forced line, else the forced symbol
                    $grid[$c][$r] = (mt_rand(1, 100) <= 25) ? "wild" : $forcedSymbol;
                } else {
                    $grid[$c][$r] = $symbols[mt_rand(0, count($symbols) - 1)];
                }
            }
        }

        // Calculate Win
        $hasWin = false;
        $winLines = [];

        for ($r = 0; $r < $rows; $r++) {
            $s1 = $grid[0][$r];
            $s2 = $grid[1][$r];
            $s3 = $grid[2][$r];

            // Filter wilds
            $nonWilds = array_filter([$s1, $s2, $s3], function($s) { return $s !== 'wild'; });
            
            // All wilds or all match non-wilds
            if (empty($nonWilds)) {
                 $hasWin = true;
                 $winLines[] = $r;
            } else {
                // Check if all non-wilds are the same
                $first = reset($nonWilds);
                $allMatch = true;
                foreach ($nonWilds as $s) {
                    if ($s !== $first) {
                        $allMatch = false;
                        break;
                    }
                }
                if ($allMatch) {
                    $hasWin = true;
                    $winLines[] = $r;
                }
            }
        }

        $winAmount = 0;
        if ($hasWin) {
            $isBigWin = (mt_rand(1, 100) <= 15);
            $multiplier = $isBigWin ? 10 : 3;
            $winAmount = $bet * $multiplier;

            $user->interest_wallet += $winAmount;
            $user->save();

             // Transaction for Win
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $winAmount;
            $transaction->post_balance = $user->interest_wallet;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->details = 'Win in Fortune Maya';
            $transaction->trx = getTrx();
            $transaction->remark = 'game_win';
            $transaction->save();
        }

        return response()->json([
            'balance' => $user->interest_wallet,
            'grid' => $grid,
            'win' => $hasWin,
            'winAmount' => $winAmount,
            'winLines' => $winLines
        ]);
    }

    public function aviator()
    {
        $pageTitle = "Aviator";
        $user = Auth::user();
        $setting = GameSetting::where('game_key', 'aviator')->firstOrFail();
        return view(activeTemplate() . 'user.games.aviator', compact('pageTitle', 'user', 'setting'));
    }

    public function aviatorBet(Request $request)
    {
        $request->validate(['bet' => 'required|numeric|min:0.1']); // Allow small bets
        $user = Auth::user();

        // 1. Check Balance
        if ($user->interest_wallet < $request->bet) {
            return response()->json(['error' => 'Insufficient balance', 'redirect' => route('user.deposit.index')], 400);
        }

        // 2. Fetch Round Data (Crash Point)
        // We trust the frontend's round ID, or fall back to the latest one
        $crashPoint = 99.99; 
        if ($request->game_id) {
             $round = AviatorRound::find($request->game_id);
             if ($round) $crashPoint = $round->crash_point;
        } else {
             $round = AviatorRound::where('status', 'waiting')->orderBy('id', 'desc')->first();
             if ($round) $crashPoint = $round->crash_point;
        }

        // 3. Validation from Game Settings
        $setting = GameSetting::where('game_key', 'aviator')->first();
        if ($setting) {
            if ($request->bet < $setting->min_bet || $request->bet > $setting->max_bet) {
                return response()->json(['error' => 'Bet amount must be between ' . $setting->min_bet . ' and ' . $setting->max_bet], 400);
            }
        }

        // 3. Deduct Real Balance
        $user->interest_wallet -= $request->bet;
        $user->save();
        
        // 4. Create Transaction
        $trx = getTrx();
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $request->bet;
        $transaction->post_balance = $user->interest_wallet;
        $transaction->charge = 0;
        $transaction->trx_type = '-';
        $transaction->details = 'Bet in Aviator';
        $transaction->trx = $trx;
        $transaction->remark = 'game_bet';
        $transaction->save();

        // 5. Create Bet Ticket
        $game = new CrashGame();
        $game->user_id = $user->id;
        $game->bet_amount = $request->bet;
        $game->crash_point = $crashPoint;
        $game->status = 0; // Running
        $game->save();

        return response()->json([
            'game_id' => $game->id, // Ticket ID used for Cashout
            'balance' => $user->interest_wallet,
            'crash_point' => $game->crash_point, 
            'status'  => 'running'
        ]);
    }

    public function aviatorCashout(Request $request)
    {
        $request->validate([
            'game_id' => 'required|integer',
            'multiplier' => 'required|numeric|min:1.01'
        ]);
        
        $game = CrashGame::where('id', $request->game_id)
                ->where('user_id', Auth::id())
                ->where('status', 0)
                ->first();

        if (!$game) {
             return response()->json(['error' => 'Game active not found or already ended'], 400);
        }
        
        // 1. Check Crash Point (Server Side Validation)
        // Note: Frontend sends round_id, but here validation relies on stored game->crash_point
        if ($request->multiplier > $game->crash_point) {
            $game->status = 2; // Loss
            $game->win_amount = 0;
            $game->save();

            // 1b. Create Loss Transaction (User Request)
            $user = Auth::user();
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = 0;
            $transaction->post_balance = $user->interest_wallet;
            $transaction->charge = 0;
            $transaction->trx_type = '-';
            $transaction->details = 'Loss in Aviator (Crashed at ' . $game->crash_point . 'x)';
            $transaction->trx = getTrx();
            $transaction->remark = 'game_loss';
            $transaction->save();

            return response()->json(['status' => 'crashed', 'crash_point' => $game->crash_point]);
        }

        // 2. User Won
        $winAmount = $game->bet_amount * $request->multiplier;
        $game->win_amount = $winAmount;
        $game->status = 1; // Win
        $game->save();

        // 3. Add Balance
        $user = Auth::user();
        $user->interest_wallet += $winAmount;
        $user->save();
        
        // 4. Create Win Transaction
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->amount = $winAmount;
        $transaction->post_balance = $user->interest_wallet;
        $transaction->charge = 0;
        $transaction->trx_type = '+';
        $transaction->details = 'Win in Aviator (x' . $request->multiplier . ')';
        $transaction->trx = getTrx();
        $transaction->remark = 'game_win';
        $transaction->save();

        return response()->json([
            'status' => 'won',
            'win_amount' => $winAmount,
            'balance' => $user->interest_wallet
        ]);
    }

    public function aviatorLost(Request $request)
    {
        $request->validate(['game_id' => 'required|integer']);
        $game = CrashGame::where('id', $request->game_id)->where('status', 0)->first();
        if ($game) {
            $game->status = 2; // Lost
            $game->win_amount = 0;
            $game->save();
            
            // Create Loss Transaction (User Request)
            $user = Auth::user();
            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = 0;
            $transaction->post_balance = $user->interest_wallet;
            $transaction->charge = 0;
            $transaction->trx_type = '-';
            $transaction->details = 'Loss in Aviator';
            $transaction->trx = getTrx();
            $transaction->remark = 'game_loss'; 
            $transaction->save();
        }
        return response()->json(['status' => 'success']);
    }
    public function aviatorAllBets()
    {
        // 1. Get truly active bets (status 0)
        $runningBets = CrashGame::where('status', 0)
            ->with('user')
            ->orderBy('id', 'desc')
            ->get();

        // 2. Get very recent completions (last 30 seconds) so they stay visible briefly
        $recentCompletions = CrashGame::where('status', '!=', 0)
            ->where('updated_at', '>=', now()->subSeconds(30))
            ->with('user')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $activeBets = $runningBets->concat($recentCompletions)->map(function($bet) {
            return [
                'user' => $bet->user->username,
                'amount' => $bet->bet_amount,
                'multiplier' => $bet->status == 1 ? (float)number_format($bet->win_amount / $bet->bet_amount, 2) : 0,
                'cashedOut' => $bet->status == 1,
                'win_amount' => $bet->win_amount
            ];
        });

        // 3. History remains for the 'My Bets' or global history records
        $history = CrashGame::where('status', '!=', 0)
            ->with('user')
            ->orderBy('id', 'desc')
            ->take(20)
            ->get()
            ->map(function($bet) {
                return [
                    'user' => $bet->user->username,
                    'amount' => $bet->bet_amount,
                    'multiplier' => $bet->status == 1 ? ($bet->win_amount / $bet->bet_amount) : 0,
                    'cashedOut' => $bet->status == 1,
                    'win_amount' => $bet->win_amount
                ];
            });
            
        // 4. Cleanup & Fetch Round History
        // Fix: Mark ALL previous 'waiting' rounds as completed so they show in history immediately
        // This handles page reloads where the previous round was technically left 'waiting'.
        $latestId = AviatorRound::max('id');
        if ($latestId) {
            AviatorRound::where('status', 'waiting')
                ->where('id', '<', $latestId)
                ->update(['status' => 'completed']);
        }
            
        $rounds = AviatorRound::where('status', '!=', 'waiting')
                  ->orderBy('id', 'desc')
                  ->take(20)
                  ->pluck('crash_point');

        return response()->json([
            'active' => $activeBets,
            'history' => $history,
            'rounds' => $rounds
        ]);
    }
    public function aviatorGameState()
    {
        $roundId = Cache::get('aviator_current_round');
        $round = $roundId ? AviatorRound::find($roundId) : AviatorRound::getCurrentRound();
        
        if (!$round) {
             return response()->json(['status' => 'waiting', 'countdown' => 5]);
        }

        $gameState = Cache::get('aviator_game_state', $round->status);
        $multiplier = Cache::get('aviator_multiplier', 1.00);
        $countdown = Cache::get('aviator_countdown', 0);
        $flyingStart = Cache::get('aviator_flying_start');

        return response()->json([
            'round_id' => $round->id,
            'round_number' => $round->round_number,
            'status'   => $gameState,
            'multiplier' => number_format($multiplier, 2),
            'crash_point' => $round->crash_point,
            'countdown' => $countdown,
            'server_time' => microtime(true),
            'flying_start' => $flyingStart
        ]);
    }





}


