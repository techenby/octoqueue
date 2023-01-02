<?php

namespace App\FilamentTeams\Resources\PrinterResource\Pages;

use App\FilamentTeams\Resources\PrinterResource;
use Artificertech\FilamentMultiContext\Concerns\ContextualPage;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrinter extends EditRecord
{
    use ContextualPage;

    protected static string $resource = PrinterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
