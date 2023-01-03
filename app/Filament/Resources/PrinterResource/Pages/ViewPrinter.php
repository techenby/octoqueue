<?php

namespace App\Filament\Resources\PrinterResource\Pages;

use App\Filament\Resources\PrinterResource;
use App\Filament\Resources\PrinterResource\Widgets\AxisControls;
use App\Filament\Resources\PrinterResource\Widgets\Camera;
use App\Filament\Resources\PrinterResource\Widgets\Connection;
use App\Filament\Resources\PrinterResource\Widgets\GeneralControls;
use App\Filament\Resources\PrinterResource\Widgets\Materials;
use App\Filament\Resources\PrinterResource\Widgets\Temperatures;
use App\Filament\Resources\PrinterResource\Widgets\ToolControls;
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
            EditAction::make()
                ->color('warning'),
            Action::make('delete')
                ->action(function () {
                    $this->record->safeDelete();
                    return redirect()->route('filament.resources.printers.index');
                })
                ->color('danger')
                ->requiresConfirmation()
                ,
        ];
    }

    public function getTitle(): string
    {
        return $this->record->name ?? 'View Printer';
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
