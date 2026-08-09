<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AviatorRound;
use App\Models\AviatorBet;
use Illuminate\Support\Facades\Cache;

class AviatorGameServer extends Command
{
    protected $signature = 'aviator:run';
    protected $description = 'Run the Aviator game server';

    private $currentMultiplier = 1.00;
    private $gameStartTime = null;

    public function handle()
    {
        $this->info('Aviator Game Server Started');

        // Ensure we have upcoming rounds
        $this->ensureUpcomingRounds();

        while (true) {
            try {
                $this->processGameCycle();
                usleep(100000); // 100ms delay
            }
            catch (\Exception $e) {
                $this->error('Error: ' . $e->getMessage());
                sleep(1);
            }
        }
    }

    private function processGameCycle()
    {
        $currentRound = AviatorRound::getCurrentRound();

        if (!$currentRound) {
            // No active round, start next pending round
            $this->startNextRound();
            return;
        }

        // Update bets cache for all connected clients
        $this->updateBetsCache($currentRound);

        if ($currentRound->status === 'waiting') {
            // Check if waiting period is over (5 seconds)
            $startedAt = $currentRound->started_at->timestamp;
            $now = $this->getCurrentTime();
            $elapsed = $now - $startedAt;

            // Store countdown in cache for frontend
            Cache::put('aviator_countdown', max(0, 5 - $elapsed), 10);
            Cache::put('aviator_round_id', $currentRound->id, 10);

            if ($elapsed >= 5 || $elapsed < -10) {
                $this->info("Round #{$currentRound->round_number} - Starting Flight");
                $this->startFlying($currentRound);
            }
            else {
            // Updating cache is enough for polling clients
            }
        }
        elseif ($currentRound->status === 'flying') {
            Cache::put('aviator_round_id', $currentRound->round_number, 10); // Sync ID
            $this->updateFlying($currentRound);

            // PUSH FLYING STATE
            $this->pushToPusher([
                'type' => 'state',
                'status' => 'flying',
                'multiplier' => round($this->currentMultiplier, 2),
                'flying_start' => Cache::get('aviator_flying_start'),
                'round_id' => $currentRound->id,
                'round_number' => $currentRound->round_number,
                'server_time' => microtime(true)
            ]);
        }
    }

    private function startNextRound()
    {
        $nextRound = AviatorRound::where('status', 'pending')
            ->orderBy('round_number', 'asc')
            ->first();

        if (!$nextRound) {
            // Create new round if none pending
            $nextRound = AviatorRound::createNextRound();
        }

        $nextRound->status = 'waiting';
        $nextRound->started_at = date('Y-m-d H:i:s', $this->getCurrentTime());
        $nextRound->save();

        // Store in cache for quick access
        Cache::put('aviator_current_round', $nextRound->id, 60);
        Cache::put('aviator_game_state', 'waiting', 60);
        Cache::put('aviator_countdown', 5, 10);

        $this->info("Round #{$nextRound->round_number} started - Waiting phase");
    }

    private function startFlying($round)
    {
        $round->status = 'flying';
        $round->save();

        $this->gameStartTime = $this->getCurrentTime();
        $this->currentMultiplier = 1.00;

        Cache::put('aviator_game_state', 'flying', 60);
        Cache::put('aviator_multiplier', 1.00, 60);
        Cache::put('aviator_flying_start', $this->gameStartTime, 60);

        $this->info("Round #{$round->round_number} - Flying started!");
    }

    private function updateFlying($round)
    {
        // Calculate current multiplier based on time elapsed
        $flyingStart = Cache::get('aviator_flying_start', $this->getCurrentTime());
        $elapsed = ($this->getCurrentTime() - $flyingStart);

        // Multiplier increases by 0.2 every second for excitement
        $this->currentMultiplier = 1.00 + ($elapsed * 0.20);

        if (round($this->currentMultiplier * 10) % 10 == 0) {
            $this->info("Round #{$round->round_number} - {$this->currentMultiplier}x");
        }

        // Store current multiplier in cache
        Cache::put('aviator_multiplier', round($this->currentMultiplier, 2), 60);

        // Process auto-cashouts
        $this->processAutoCashouts($round, $this->currentMultiplier);

        // Check if should crash
        if ($this->currentMultiplier >= $round->crash_point) {
            $this->crashRound($round);
        }
    }

    private function processAutoCashouts($round, $currentMultiplier)
    {
        $autoCashoutBets = AviatorBet::where('aviator_round_id', $round->id)
            ->where('status', 'active')
            ->where('auto_cashout', true)
            ->where('auto_cashout_at', '<=', $currentMultiplier)
            ->get();

        foreach ($autoCashoutBets as $bet) {
            if ($bet->shouldAutoCashout($currentMultiplier)) {
                $bet->cashOut($currentMultiplier);
                $this->info("Auto cash-out: User #{$bet->user_id} at {$currentMultiplier}x");
            }
        }
    }

    private function crashRound($round)
    {
        $round->status = 'crashed';
        $round->crashed_at = now();
        $round->save();

        // Mark all remaining active bets as lost
        $activeBets = $round->activeBets;
        foreach ($activeBets as $bet) {
            $bet->markAsLost();
        }

        // Update statistics
        $round->updateStatistics();

        // Update cache
        Cache::put('aviator_game_state', 'crashed', 60);
        Cache::put('aviator_multiplier', $round->crash_point, 60);

        // Polling clients read from cache, no push needed

        $this->info("Round #{$round->round_number} CRASHED at {$round->crash_point}x");

        // Create next round
        AviatorRound::createNextRound();

        // Wait 3 seconds before starting next round
        sleep(3);
    }

    private function ensureUpcomingRounds()
    {
        $pendingCount = AviatorRound::where('status', 'pending')->count();

        if ($pendingCount < 5) {
            $toCreate = 10 - $pendingCount;
            for ($i = 0; $i < $toCreate; $i++) {
                AviatorRound::createNextRound();
            }
            $this->info("Created {$toCreate} upcoming rounds");
        }
    }

    private function updateBetsCache($round)
    {
        $bets = AviatorBet::where('aviator_round_id', $round->id)
            ->with('user')
            ->get()
            ->map(function ($bet) {
            return [
            'id' => $bet->id,
            'username' => $bet->user->username ?? 'User',
            'bet_amount' => $bet->bet_amount,
            'cashout_multiplier' => $bet->cashout_multiplier,
            'payout' => $bet->payout,
            'status' => $bet->status,
            'round_id' => $bet->aviator_round_id
            ];
        });

        Cache::put('aviator_current_bets', $bets->toArray(), 10);
    }

    private $timeOffset = 0;
    private $timeSynced = false;

    private function getCurrentTime()
    {
        return microtime(true) + $this->timeOffset;
    }
}
