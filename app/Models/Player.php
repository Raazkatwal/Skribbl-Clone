<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'room_id',
        'user_id',
        'name',
        'score',
        'is_drawer',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
