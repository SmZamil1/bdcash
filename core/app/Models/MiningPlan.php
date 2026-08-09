<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiningPlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function timeSetting()
    {
        return $this->belongsTo(TimeSetting::class);
    }
}
