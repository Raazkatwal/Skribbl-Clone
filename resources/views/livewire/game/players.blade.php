            <div>
                @forelse ($players as $player)
                    <div class="flex h-12 items-center justify-between bg-gray-200 px-3 odd:bg-gray-300">
                        <div># {{ $loop->iteration }}</div>
                        <div class="flex flex-col items-center gap-0.5">
                            <span class="text-sm text-blue-600">
                                {{ $player->user->name }}
                                {{ $player->user_id === auth()->id() ? '(You)' : '' }}
                            </span>
                            <span class="text-xs">0 Points</span>
                        </div>
                        <div class="size-12 bg-cover bg-no-repeat"
                            style="
                                background-image: url('{{ asset('images/color_atlas.gif') }}');
                                background-position: 0px -2px;
                                background-size: 480px 480px;
                            ">
                        </div>
                    </div>
                @empty
                    <div class="flex h-12 items-center justify-between bg-gray-200 px-3 odd:bg-gray-300">
                        <div># 999</div>
                        <div class="flex flex-col items-center gap-0.5">
                            <span class="text-sm text-blue-600">
                                No Players Found
                            </span>
                            <span class="text-xs">0 Points</span>
                        </div>
                        <div class="size-12 bg-cover bg-no-repeat"
                            style="
                                background-image: url('{{ asset('images/color_atlas.gif') }}');
                                background-position: 0px -2px;
                                background-size: 480px 480px;
                            ">
                        </div>
                    </div>
                @endforelse
            </div>
