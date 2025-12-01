<?php

namespace App\Models;

use App\Enums\RoomStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    protected $fillable = [
        'name',
        'code',
        'max_players',
        'rounds',
        'round_time',
        'status',
        'current_round',
        'current_word',
        'current_drawer_id',
        'round_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => RoomStatus::class,
            'current_word' => 'encrypted',
        ];
    }

    /**
     * @return BelongsTo<User,Room>
     */
    public function drawer(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
