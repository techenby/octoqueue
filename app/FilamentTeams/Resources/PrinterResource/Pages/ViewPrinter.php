<?php

namespace App\FilamentTeams\Resources\PrinterResource\Pages;

use App\FilamentTeams\Resources\PrinterResource;
use App\FilamentTeams\Resources\PrinterResource\Widgets\PrinterCamera;
use Filament\Resources\Pages\ViewRecord;

class ViewPrinter extends ViewRecord
{
    protected static string $resource = PrinterResource::class;

    public function getFooterWidgets(): array
    {
        return [
            PrinterCamera::class,
        ];
    }
}
