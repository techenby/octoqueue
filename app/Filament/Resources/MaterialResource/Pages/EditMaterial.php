<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMaterial extends EditRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
