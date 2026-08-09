<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAiManager extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'next_withdraw_at' => 'datetime',
        'last_run' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
