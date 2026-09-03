<?php

namespace App\Livewire\Game;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class WordPicker extends Component
{
    public function render(): View
    {
        return view('livewire.game.word-picker');
    }
}
