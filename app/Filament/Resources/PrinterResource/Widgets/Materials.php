<?php

namespace App\Filament\Resources\PrinterResource\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Materials extends Widget
{
    public ?Model $record = null;

    protected static string $view = 'filament.resources.printer-resource.widgets.materials';

    protected $rules = [
        'record.material_id' => 'nullable',
    ];

    public function mount()
    {
        parent::mount();
    }

    public function updatedRecord($value, $key)
    {
        $this->validate();

        $this->record->update([
            'material_id' => ($value === '') ? null : $value,
        ]);

        Notification::make()
            ->title("{$this->record->name} material updated successfully.")
            ->success()
            ->duration(5000)
            ->send();
    }

    public function getViewData(): array
    {
        return [
            'materialOptions' => auth()->user()->currentTeam->materials->pluck('name', 'id'),
        ];
    }
}
