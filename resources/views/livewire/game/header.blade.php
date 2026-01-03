<div
    class="col-span-3 flex items-center justify-between rounded bg-gradient-to-r from-blue-500 to-purple-500 px-2 shadow-md">
    <div class="flex items-center gap-4" x-data="{ remaining: 0, interval: null }" x-init="$wire.on('countdown-start', e => {
        remaining = e.seconds;

        if (interval) clearInterval(interval); // stop any existing interval

        interval = setInterval(() => {
            if (remaining > 0) {
                remaining--;
            } else {
                clearInterval(interval);
            }
        }, 1000);
    });">
        <div class="grid size-12 place-items-center bg-cover bg-no-repeat font-extrabold"
            style="background-image: url('{{ asset('images/clock.gif') }}');
            background-position: 0 -3px;"
            x-text="remaining">
        </div>

        <span class="font-bold">Round 1 of 8</span>
    </div>
    <span class="font-semibold tracking-widest text-white uppercase">waiting</span>
    <div class="flex gap-4">
        <div class="size-12 cursor-pointer bg-cover bg-no-repeat"
            style="background-image: url('{{ asset('images/settings.gif') }}')"></div>
        <button wire:click="removePlayer" class="rounded bg-red-500 px-3 py-1 text-white transition hover:bg-red-600">
            Leave Game
        </button>
    </div>
</div>
