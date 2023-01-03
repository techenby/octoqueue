<?php

namespace App\Filament\Resources\PrinterResource\Pages;

use App\Filament\Resources\PrinterResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePrinter extends CreateRecord
{
    protected static string $resource = PrinterResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create(array_merge($data, ['team_id' => auth()->user()->currentTeam->id]));
    }
}
