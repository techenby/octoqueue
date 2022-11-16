<?php

namespace App\Filament\Resources\PrinterResource\Pages;

use App\Filament\Resources\PrinterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrinters extends ListRecords
{
    protected static string $resource = PrinterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
