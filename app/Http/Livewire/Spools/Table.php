<?php

namespace App\Http\Livewire\Spools;

use Livewire\Component;

class Table extends Component
{
    public $spools;

    public function render()
    {
        return view('livewire.spools.table');
    }
}
