<div class="flex items-center justify-center min-h-screen">
    <form wire:submit.prevent="join"
          class="bg-[#123595] p-8 rounded-xl shadow-lg w-full max-w-md space-y-6">

        <h1 class="text-2xl font-bold text-center text-white">Join or Create a Game</h1>

        <div>
            <input
                type="text"
                wire:model.blur="username"
                placeholder="Enter your name"
                class="w-full px-4 py-2 border text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500
                {{ $errors->has('username') ? 'border-red-500' : 'border-gray-300' }}"
            >
            @error('username') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <input
                type="text"
                wire:model.blur="room_code"
                placeholder="Enter room code"
                class="w-full px-4 py-2 border border-gray-300 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500
                {{ $errors->has('room_code') ? 'border-red-500' : 'border-gray-300' }}"
            >
            @error('room_code') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <button
            type="submit"
            class="w-full bg-[#53e237] cursor-pointer text-white py-2 rounded-lg hover:bg-indigo-700 transition duration-200 font-semibold"
        >
            Join Game
        </button>
    </form>
</div>
