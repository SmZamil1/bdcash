<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $casts = [
        'permissions' => 'array',
    ];

    public function admins()
    {
        return $this->hasMany(Admin::class, 'role_id');
    }
}
