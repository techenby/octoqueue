<?php

namespace App\Filament\Resources\PrintTypeResource\Pages;

use App\Filament\Resources\PrintTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrintTypes extends ListRecords
{
    protected static string $resource = PrintTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
