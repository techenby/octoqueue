<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
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
            'tools' => $this->printer->tools->mapWithKeys(function ($tool) {
                return [$tool->id => ['name' => $tool->name, 'material_id' => $tool->material_id]];
            })->toArray()
        ]);
    }

    public function connect()
    {
        // TODO
    }

    public function deletePrinter()
    {
        $this->printer->safeDelete();

        return redirect('printers');
    }

    public function fetchStatus()
    {
        // TODO
    }
}
