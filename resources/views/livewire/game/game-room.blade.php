<div class="grid h-screen w-screen place-items-center">
    <div class="size-11/12">
        <div class="logo m-auto"></div>

        <div class="grid size-full grid-cols-[20%_60%_20%] grid-rows-[10%_90%] gap-1.5">

            <livewire:game.header :room="$room" />

            <livewire:game.players :room="$room" />

            <livewire:game.canvas
                :room="$room"
                :is-drawer="$isDrawer"
                :max-players="$max_players"
                :rounds="$rounds"
                :drawtime="$drawtime"
                :is-host="session('is_host')"
            />

            <livewire:game.chat />

        </div>
    </div>
</div>
