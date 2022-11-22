<?php

namespace App\Http\Livewire\Bit;

use Filament\Notifications\Notification;
use Illuminate\Support\Str;
use Livewire\Component;

class AssignMaterial extends Component
{
    public $tools;

    public $rules = [
        'tools.*.material_id' => 'nullable',
    ];

    public function updatedTools($value, $key)
    {
        $this->validate();

        $tool = $this->tools[Str::before($key, '.')];

        $tool->update([
            'material_id' => ($value === '') ? null : $value
        ]);

        Notification::make()
            ->title("{$tool->name} updated successfully.")
            ->success()
            ->duration(5000)
            ->send();
    }

    public function render()
    {
        return view('livewire.bit.assign-material', [
            'materialOptions' => auth()->user()->currentTeam->materials->pluck('name', 'id'),
        ]);
    }
}
