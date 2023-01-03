<?php

namespace App\Filament\Resources\PrinterResource\Widgets;

use App\Jobs\FetchPrinterStatus;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Connection extends Widget implements HasForms
{
    use InteractsWithForms;

    public ?Model $record = null;

    public $autoconnect;

    public $baudrate;

    public $connectionOptions;

    public $port;

    public $printerProfile;

    public $save;

    protected static string $view = 'filament.resources.printer-resource.widgets.connection';

    public function mount()
    {
        parent::mount();

        $this->loadConnection();
    }

    public function connect()
    {
        $response = Http::octoPrint($this->record)
            ->post('api/connection', array_filter(array_merge($this->form->getState(), [
                'command' => 'connect',
            ])));

        if ($response->failed()) {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        } else {
            FetchPrinterStatus::dispatch($this->record);

            $this->loadConnection();
        }
    }

    public function disconnect()
    {
        $response = Http::octoPrint($this->record)->post('api/connection', ['command' => 'disconnect']);

        if ($response->failed()) {
            Notification::make()
                ->title($response->json('error'))
                ->danger()
                ->send();
        } else {
            FetchPrinterStatus::dispatch($this->record);

            $this->loadConnection();
        }
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('port')
                ->disabled(in_array($this->record->status, ['operational', 'printing']))
                ->options(array_combine($this->connectionOptions['ports'], $this->connectionOptions['ports']))
                ->placeholder('AUTO'),
            Select::make('baudrate')
                ->disabled(in_array($this->record->status, ['operational', 'printing']))
                ->options(array_combine($this->connectionOptions['baudrates'], $this->connectionOptions['baudrates']))
                ->placeholder('AUTO'),
            Select::make('printerProfile')
                ->disabled(in_array($this->record->status, ['operational', 'printing']))
                ->options(collect($this->connectionOptions['printerProfiles'])->pluck('name', 'id'))
                ->required(),
            Checkbox::make('save')
                ->disabled(in_array($this->record->status, ['operational', 'printing']))
                ->label('Save connection settings'),
            Checkbox::make('autoconnect')
                ->disabled(in_array($this->record->status, ['operational', 'printing']))
                ->label('Auto-connect on startup'),
        ];
    }

    private function loadConnection()
    {
        $response = Http::octoPrint($this->record)->get('/api/connection')->json();

        $this->connectionOptions = $response['options'];

        $this->form->fill([
            'baudrate' => $response['current']['baudrate'] ?? $response['options']['baudratePreference'],
            'port' => $response['current']['port'] ?? $response['options']['portPreference'],
            'printerProfile' => $response['current']['printerProfile'] ?? $response['options']['printerProfilePreference'],
            'autoconnect' => $this->connectOptions['autoconnect'] ?? false,
        ]);
    }
}
