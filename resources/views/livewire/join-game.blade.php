<div class="flex min-h-screen items-center justify-center">
    <form
        wire:submit.prevent="join"
        class="w-full max-w-md space-y-6 rounded-xl bg-[#123595] p-8 shadow-lg"
    >
        <h1 class="text-center text-2xl font-bold text-white">
            Join or Create a Game
        </h1>

        <div>
            <input
                type="text"
                wire:model.blur="username"
                placeholder="Enter your name"
                class="w-full px-4 py-2 border text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500
                {{ $errors->has('username') ? 'border-red-500' : 'border-gray-300' }}"
            />
            @error('username')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <input
                type="text"
                wire:model.blur="room_code"
                placeholder="Enter room code"
                class="w-full px-4 py-2 border border-gray-300 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500
                {{ $errors->has('room_code') ? 'border-red-500' : 'border-gray-300' }}"
            />
            @error('room_code')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full cursor-pointer rounded-lg bg-[#53e237] py-2 font-semibold text-white transition duration-200 hover:bg-indigo-700"
        >
            Join Game
        </button>
    </form>
</div>
