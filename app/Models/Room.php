<?php

namespace App\Models;

use App\Enums\RoomStatus;
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

    protected function casts(): array
    {
        return [
            'status' => RoomStatus::class,
        ];
    }
}
