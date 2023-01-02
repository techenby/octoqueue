<?php

namespace App\FilamentTeams\Resources\PrinterResource\Pages;

use App\FilamentTeams\Resources\PrinterResource;
use Artificertech\FilamentMultiContext\Concerns\ContextualPage;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPrinters extends ListRecords
{
    use ContextualPage;

    protected static string $resource = PrinterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
