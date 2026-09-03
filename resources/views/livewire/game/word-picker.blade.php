<div x-data="{
    open: false,
    words: [],
    choose(word) {
        this.open = false;
        $wire.dispatch('word-selected', { word: word });
    }
}" x-on:show-word-picker.window="words = $event.detail.words; open = !$event.detail.current_word;">
    <!-- Overlay -->
    <div x-show="open" x-cloak x-transition.opacity class="absolute inset-0 z-20 grid place-items-center bg-black/60">
        <div class="w-96 rounded-lg bg-white p-6 shadow-xl">
            <h2 class="mb-4 text-center text-lg font-bold">Choose a word</h2>

            <div class="grid grid-cols-3 gap-3">
                <template x-for="word in words" :key="word">
                    <button
                        class="cursor-pointer rounded border border-blue-500 px-4 py-2 font-semibold text-black hover:bg-blue-600 hover:text-white"
                        @click="choose(word)" x-text="word"></button>
                </template>
            </div>

            <p class="mt-4 text-center text-sm text-gray-500">
                You are the drawer 🎨
            </p>
        </div>
    </div>
</div>
