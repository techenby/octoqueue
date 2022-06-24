<?php

namespace App\Http\Livewire\Bit;

use App\Models\Printer;
use Livewire\Component;

class NextJob extends Component
{
    public Printer $printer;

    public function print()
    {
        $this->printer->nextJob->start();
    }
}
