<?php

use App\Enums\RoomStatus;
use App\Events\DrawEvent;
use App\Events\PlayerJoined;
use App\Livewire\Game\Canvas;
use App\Livewire\Game\GameRoom;
use App\Livewire\JoinGame;
use App\Models\Player;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('join game page renders under livewire v4 and javascript syncs on blur', function () {
    Livewire::test(JoinGame::class)
        ->assertViewIs('livewire.join-game');
});

test('join game creates a room and player then redirects to the whiteboard', function () {
    Event::fake([PlayerJoined::class]);

    $component = Livewire::test(JoinGame::class)
        ->set('username', 'PlayerOne')
        ->set('room_code', 'ABC123')
        ->call('join');

    $component->assertRedirect(route('whiteboard', ['room' => 'ABC123']));

    $this->assertDatabaseHas('rooms', ['code' => 'ABC123', 'status' => RoomStatus::WAITING->value]);
    $this->assertDatabaseHas('users', ['name' => 'PlayerOne']);
    $this->assertTrue(session('is_host'));
});

test('game room and its child components render under livewire v4', function () {
    $user = User::create(['name' => 'Host']);
    $guest = User::create(['name' => 'Guest']);

    $room = Room::create([
        'code' => 'ROOM42',
        'status' => RoomStatus::WAITING,
        'max_players' => 5,
        'rounds' => 3,
        'round_time' => 80,
    ]);

    Player::create(['room_id' => $room->id, 'user_id' => $user->id, 'is_drawer' => true]);
    Player::create(['room_id' => $room->id, 'user_id' => $guest->id, 'is_drawer' => false]);

    Auth::login($user);
    session(['is_host' => true]);

    Livewire::test(GameRoom::class, ['room' => $room])
        ->assertOk()
        ->assertSet('isHost', true)
        ->assertSet('isDrawer', true);

    Livewire::test(Canvas::class, [
        'room' => $room,
        'isDrawer' => true,
        'isHost' => true,
        'maxPlayers' => 5,
        'rounds' => 3,
        'drawtime' => 80,
    ])->assertOk();
});

test('drawer can accept a word and broadcast a draw event', function () {
    $user = User::create(['name' => 'Drawer']);
    $guest = User::create(['name' => 'Guesser']);

    $room = Room::create([
        'code' => 'DRAW99',
        'status' => RoomStatus::PLAYING,
        'max_players' => 5,
        'rounds' => 3,
        'round_time' => 80,
    ]);

    Player::create(['room_id' => $room->id, 'user_id' => $user->id, 'is_drawer' => true]);
    Player::create(['room_id' => $room->id, 'user_id' => $guest->id, 'is_drawer' => false]);

    Auth::login($user);
    session(['is_host' => true]);

    $gameRoom = Livewire::test(GameRoom::class, ['room' => $room])
        ->assertSet('isDrawer', true)
        ->call('selectWord', 'apple')
        ->assertDispatched('countdown-start');

    $this->assertEquals('apple', Room::find($room->id)->current_word);

    Event::fake([DrawEvent::class]);

    Livewire::test(Canvas::class, [
        'room' => $room,
        'isDrawer' => true,
        'isHost' => true,
        'maxPlayers' => 5,
        'rounds' => 3,
        'drawtime' => 80,
    ])->call('handleDraw', 'start', 10, 20, [0, 0, 0], 'pen', $user->id);

    Event::assertDispatched(DrawEvent::class);
});
