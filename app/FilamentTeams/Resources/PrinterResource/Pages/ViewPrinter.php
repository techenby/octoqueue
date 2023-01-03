<?php

namespace App\FilamentTeams\Resources\PrinterResource\Pages;

use App\FilamentTeams\Resources\PrinterResource;
use App\FilamentTeams\Resources\PrinterResource\Widgets\AxisControls;
use App\FilamentTeams\Resources\PrinterResource\Widgets\Camera;
use App\FilamentTeams\Resources\PrinterResource\Widgets\Connection;
use App\FilamentTeams\Resources\PrinterResource\Widgets\GeneralControls;
use App\FilamentTeams\Resources\PrinterResource\Widgets\Materials;
use App\FilamentTeams\Resources\PrinterResource\Widgets\Temperatures;
use App\FilamentTeams\Resources\PrinterResource\Widgets\ToolControls;
use App\Jobs\FetchPrinterStatus;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPrinter extends ViewRecord
{
    protected static string $resource = PrinterResource::class;

    protected static string $view = 'filament.resources.pages.view-record-without-form';

    protected function getActions(): array
    {
        return [
            Action::make('fetch_status')
                ->action(fn () => FetchPrinterStatus::dispatch($this->record))
                ->color('secondary'),
            EditAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return $this->record->name;
    }

    public function getSubheading(): string
    {
        return "{$this->record->model} · {$this->record->url} · {$this->record->status}";
    }

    public function getFooterWidgets(): array
    {
        return [
            Camera::class,
            AxisControls::class,
            Temperatures::class,
            Materials::class,
            Connection::class,
            GeneralControls::class,
            ToolControls::class,
        ];
    }

    protected function getFooterWidgetsColumns(): int|array
    {
        return [
            'lg' => 4,
        ];
    }
}
