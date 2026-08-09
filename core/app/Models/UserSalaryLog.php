<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSalaryLog extends Model
{
    protected $guarded = ['id'];

    public function userSalary()
    {
        return $this->belongsTo(UserSalary::class);
    }
}
