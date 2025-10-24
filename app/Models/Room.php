<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'code',
        'max_players',
        'rounds',
        'round_time',
        'status',
    ];
}
