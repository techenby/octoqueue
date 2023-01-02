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
use Filament\Resources\Pages\ViewRecord;

class ViewPrinter extends ViewRecord
{
    protected static string $resource = PrinterResource::class;

    public function getFooterWidgets(): array
    {
        return [
            Camera::class,
            AxisControls::class,
            Temperatures::class,
            Connection::class,
            GeneralControls::class,
            Materials::class,
            ToolControls::class,
        ];
    }

    protected function getFooterWidgetsColumns(): int | array
    {
        return [
            'lg' => 4,
        ];
    }
}
