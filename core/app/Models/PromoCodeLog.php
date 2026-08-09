<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCodeLog extends Model
{
    protected $table = 'promocode_logs';
    protected $guarded = ['id'];

    public function promocode()
    {
        return $this->belongsTo(PromoCode::class, 'promocode_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
