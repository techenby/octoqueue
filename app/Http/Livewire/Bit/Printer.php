<?php

namespace App\Http\Livewire\Bit;

use App\Models\Printer as Model;
use Livewire\Component;

class Printer extends Component
{
    public Model $printer;

    public function render()
    {
        return view('livewire.bit.printer');
    }
}
