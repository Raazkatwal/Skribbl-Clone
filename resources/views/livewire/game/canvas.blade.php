<div class="relative">
    <livewire:game.start-overlay :room="$room" :is-host="$isHost" :max-players="$maxPlayers" :rounds="$rounds"
        :drawtime="$drawtime" />

    <livewire:game.word-picker />

    <canvas wire:ignore id="board" width="750" height="540" class="rounded bg-white" x-init="$wire.$watch('isDrawer', value => window.canDraw = value)"></canvas>

    <livewire:game.tool-bar />
</div>
