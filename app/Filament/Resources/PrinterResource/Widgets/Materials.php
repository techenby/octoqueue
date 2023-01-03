<?php

namespace App\Filament\Resources\PrinterResource\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Materials extends Widget
{
    public ?Model $record = null;

    public $tools;

    protected static string $view = 'filament.resources.printer-resource.widgets.materials';

    protected $rules = [
        'tools.*.material_id' => 'nullable',
    ];

    public function mount()
    {
        parent::mount();

        $this->tools = $this->record->tools;
    }

    public function updatedTools($value, $key)
    {
        $this->validate();

        $tool = $this->tools[Str::before($key, '.')];

        $tool->update([
            'material_id' => ($value === '') ? null : $value,
        ]);

        Notification::make()
            ->title("{$tool->name} updated successfully.")
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
