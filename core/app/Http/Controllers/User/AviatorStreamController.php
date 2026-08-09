<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AviatorRound;
use App\Models\AviatorBet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use DB;

class AviatorStreamController extends Controller
{
    public function stream()
    {
        set_time_limit(0); // Prevent timeout
        
        // 1. Headers for SSE
        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no'); // Nginx
        header('Content-Encoding: none'); // Optimize for SPanel/Litespeed
        header('Access-Control-Allow-Origin: *');
        
        // 2. Close session to allow other requests (bets) to proceed
        session_write_close();
        
        $user = Auth::user();
        $counter = 0;
        
        // 3. Loop
        $lastDataJson = '';
        while (true) {
            if (connection_aborted() || $counter > 2400) { 
                break;
            }
            $counter++;
            
            $microNow = microtime(true);
            $fraction = $microNow - floor($microNow);
            $data = $this->getGameState($user, $fraction);
            
            $currentDataJson = json_encode($data);
            if ($currentDataJson !== $lastDataJson) {
                echo "data: " . $currentDataJson . "\n\n";
                $lastDataJson = $currentDataJson;
                
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();
            }
            
            usleep(100000); 
        }
    }
    
    private function getGameState($user, $fraction = 0)
    {
        $roundId = \Cache::get('aviator_current_round');
        $currentRound = $roundId ? AviatorRound::find($roundId) : AviatorRound::getCurrentRound();
        
        $gameState = \Cache::get('aviator_game_state', $currentRound->status ?? 'waiting');
        $multiplier = \Cache::get('aviator_multiplier', 1.00);
        $countdown = \Cache::get('aviator_countdown', 0);
        $flyingStart = \Cache::get('aviator_flying_start');

        $bets = [];
        $userBet = null;

        if ($currentRound) {
            // Optimization: Only fetch bets if needed, or send simplified list
            $bets = $currentRound->bets()->with('user:id,username')
                ->orderBy('created_at', 'desc')
                ->take(50)
                ->get()
                ->map(function($bet) {
                     return [
                        'id' => $bet->id,
                        'username' => $bet->user->username ?? 'Player',
                        'bet_amount' => $bet->bet_amount,
                        'cashout_multiplier' => $bet->cashout_multiplier,
                        'payout' => $bet->payout,
                        'status' => $bet->status,
                    ];
                });
                
            if ($user) {
                $userBet = $currentRound->bets()
                    ->where('user_id', $user->id)
                    ->first();
            }
        } else {
             // Show last crash for 2 seconds
             $dbNow = time();
             $lastRound = AviatorRound::where('status', 'crashed')->orderBy('id', 'desc')->first();
             if ($lastRound && ($dbNow - $lastRound->crashed_at?->timestamp) < 2) {
                 $gameState = 'crashed';
                 $multiplier = $lastRound->crash_point;
                 $currentRound = $lastRound; 
             }
        }
        
        return [
            'game_state' => $gameState,
            'multiplier' => is_numeric($multiplier) ? floatval($multiplier) : 1.00,
            'countdown' => $countdown,
            'server_time' => microtime(true),
            'flying_start' => $gameState === 'flying' ? \Cache::get('aviator_flying_start') : null,
            'round' => $currentRound ? [
                'id' => $currentRound->id,
                'round_number' => $currentRound->round_number,
                'crash_point' => $gameState === 'crashed' ? $currentRound->crash_point : null,
                'status' => $currentRound->status,
            ] : null,
            'bets' => $bets,
            'user_bet' => $userBet,
            'balance' => $user ? $user->interest_wallet : 0,
        ];
    }
}
