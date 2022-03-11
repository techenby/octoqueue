<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Livewire\Component;

class Table extends Component
{
    public $printers;

    public function render()
    {
        return view('livewire.printers.table');
    }
}
