<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AviatorBet extends Model
{
    protected $fillable = [
        'user_id',
        'aviator_round_id',
        'bet_amount',
        'cashout_multiplier',
        'payout',
        'status',
        'auto_cashout',
        'auto_cashout_at',
        'cashed_out_at'
    ];

    protected $casts = [
        'bet_amount' => 'decimal:8',
        'cashout_multiplier' => 'decimal:2',
        'payout' => 'decimal:8',
        'auto_cashout' => 'boolean',
        'auto_cashout_at' => 'decimal:2',
        'cashed_out_at' => 'datetime',
    ];

    /**
     * Get the user who placed this bet
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the round this bet belongs to
     */
    public function round(): BelongsTo
    {
        return $this->belongsTo(AviatorRound::class, 'aviator_round_id');
    }

    /**
     * Cash out the bet at current multiplier
     */
    public function cashOut($multiplier)
    {
        if ($this->status !== 'active') {
            return false;
        }

        $this->cashout_multiplier = $multiplier;
        $this->payout = $this->bet_amount * $multiplier;
        $this->status = 'cashed_out';
        $this->cashed_out_at = now();
        $this->save();

        // Update user balance
        $this->user->interest_wallet += $this->payout;
        $this->user->save();

        return true;
    }

    /**
     * Mark bet as lost
     */
    public function markAsLost()
    {
        if ($this->status !== 'active') {
            return false;
        }

        $this->status = 'lost';
        $this->payout = 0;
        $this->save();

        return true;
    }

    /**
     * Check if auto cashout should trigger
     */
    public function shouldAutoCashout($currentMultiplier)
    {
        return $this->auto_cashout && 
               $this->status === 'active' && 
               $currentMultiplier >= $this->auto_cashout_at;
    }
}
