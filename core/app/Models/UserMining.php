<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMining extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'last_paid' => 'datetime',
        'next_profit_at' => 'datetime',
    ];

    public function miningPlan()
    {
        return $this->belongsTo(MiningPlan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
