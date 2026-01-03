<div x-data="{
    open: false,
    words: [],
    choose(word) {
        this.open = false;
        $wire.call('selectWord', word);
    }
}" x-init="$wire.on('show-word-picker', e => {
    words = e.words;
    open = true;
});">
    <!-- Overlay -->
    <div x-show="open" x-transition.opacity class="absolute inset-0 grid place-items-center bg-black/60">
        <div class="w-96 rounded-lg bg-white p-6 shadow-xl">
            <h2 class="mb-4 text-center text-lg font-bold">
                Choose a word
            </h2>

            <div class="grid grid-cols-3 gap-3">
                <template x-for="word in words" :key="word">
                    <button
                        class="rounded border border-blue-500 px-4 py-2 font-semibold cursor-pointer text-black hover:bg-blue-600 hover:text-white"
                        @click="choose(word)" x-text="word"></button>
                </template>
            </div>

            <p class="mt-4 text-center text-sm text-gray-500">
                You are the drawer 🎨
            </p>
        </div>
    </div>
</div>
