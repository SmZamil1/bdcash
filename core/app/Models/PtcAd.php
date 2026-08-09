<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PtcAd extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function logs()
    {
        return $this->hasMany(PtcAdLog::class, 'ad_id');
    }
}
