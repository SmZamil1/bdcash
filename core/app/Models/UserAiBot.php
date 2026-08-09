<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAiBot extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'last_profit_at' => 'datetime',
        'next_profit_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bot()
    {
        return $this->belongsTo(AiBot::class , 'ai_bot_id');
    }
}
