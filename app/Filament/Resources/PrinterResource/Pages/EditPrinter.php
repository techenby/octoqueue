<?php

namespace App\Filament\Resources\PrinterResource\Pages;

use App\Filament\Resources\PrinterResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrinter extends EditRecord
{
    protected static string $resource = PrinterResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
