<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppPlayStoreSetting extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $casts = [
        'screenshots' => 'array',
        'tags' => 'array'
    ];
}
