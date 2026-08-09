<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiBot extends Model
{
    protected $guarded = ['id'];

    public function timeSetting()
    {
        return $this->belongsTo(TimeSetting::class);
    }

    protected $casts = [
        'price' => 'decimal:2',
        'max_invest' => 'decimal:2',
        'daily_trade_limit' => 'integer',
        'duration' => 'integer',
        'min_profit' => 'decimal:2',
        'max_profit' => 'decimal:2',
        'daily_profit' => 'decimal:2',
    ];

}
