<?php

namespace App\FilamentTeams\Resources\PrinterResource\Widgets;

use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class ToolControls extends Widget implements HasForms
{
    use InteractsWithForms;

    public ?Model $record = null;

    protected static string $view = 'filament.resources.printer-resource.widgets.tool-controls';

    public function extrude()
    {
        if ($this->temps['tool0']['actual'] < 180) {
            return Notification::make()
                ->title('Extruder is too cold')
                ->body('The printer must be at least 180°C to extrude.')
                ->danger()
                ->send();
        }

        $amount = $this->sign == '+' ? $this->extrudeAmount : '-' . $this->extrudeAmount;

        $response = Http::octoPrint($this->record)
            ->post('api/printer/tool', [
                'command' => 'extrude',
                'amount' => (int) $amount,
            ]);

        if ($response->failed()) {
            return Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }

        Notification::make()
            ->title('Sent command to printer')
            ->body(($this->sign == '+' ? 'Extruding ' : 'Retracting ') . $this->extrudeAmount . 'mm')
            ->success()
            ->send();
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('extrudeAmount')
                ->disabled(in_array($this->record->status, ['printing', 'error', 'closed', 'offline']))
                ->minValue(0)
                ->numeric()
                ->suffix('mm'),
            Radio::make('sign')
                ->options([
                    '+' => 'Extrude',
                    '-' => 'Retract',
                ]),
        ];
    }
}
