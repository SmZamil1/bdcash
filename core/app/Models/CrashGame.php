<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrashGame extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'bet_amount' => 'decimal:2',
        'crash_point' => 'float',
        'win_amount' => 'decimal:2',
        'status' => 'integer',
        'created_at' => 'datetime',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
