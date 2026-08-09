<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class ForexPair extends Model
{
    use GlobalStatus;
    
    protected $guarded = ['id'];
}
