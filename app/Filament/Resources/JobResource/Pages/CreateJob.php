<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return static::getModel()::create(array_merge($data, ['team_id' => auth()->user()->currentTeam->id]));
    }

    public function getColorOptionsProperty()
    {
        return auth()->user()->currentTeam->materials->pluck('name', 'color_hex');
    }

    public function getPrintersProperty()
    {
        return auth()->user()->currentTeam->printers;
    }
}
