<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Livewire\Component;

class Show extends Component
{
    public Printer $printer;

    public function render()
    {
        return view('livewire.printers.show', [
            'title' => $this->printer->name,
            'breadcrumbs' => [
                ['label' => 'Printers', 'route' => 'printers'],
                ['label' => $this->printer->name],
            ],
        ]);
    }
}
