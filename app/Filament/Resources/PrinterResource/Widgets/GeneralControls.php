<?php

namespace App\Filament\Resources\PrinterResource\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class GeneralControls extends Widget
{
    protected static string $view = 'filament.resources.printer-resource.widgets.general-controls';
    public ?Model $record = null;

    public function fansOff()
    {
        $this->manualCommand('M106 S0');
    }

    public function fansOn()
    {
        $this->manualCommand('M106 S255');
    }

    public function motorsOff()
    {
        $this->manualCommand('M18');
    }

    private function manualCommand($command)
    {
        $response = Http::octoPrint($this->record)->post('api/printer/command', [
            'command' => $command,
        ]);

        if ($response->failed()) {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        }
    }
}
