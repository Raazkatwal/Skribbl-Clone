<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property int $room_id
 * @property int $user_id
 * @property int $score
 * @property int $is_drawer
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Room $room
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereIsDrawer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereRoomId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Player whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperPlayer {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property int $max_players
 * @property int $rounds
 * @property int $round_time
 * @property \App\Enums\RoomStatus $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereMaxPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereRoundTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereRounds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRoom {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $is_guest
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Player|null $player
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereIsGuest($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

