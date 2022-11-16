<?php

namespace App\Filament\Resources\PrintTypeResource\Pages;

use App\Filament\Resources\PrintTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPrintType extends EditRecord
{
    protected static string $resource = PrintTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
