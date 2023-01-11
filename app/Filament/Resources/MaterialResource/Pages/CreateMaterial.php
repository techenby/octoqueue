<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateMaterial extends CreateRecord
{
    protected static string $resource = MaterialResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $material = static::getModel()::create(array_merge($data, [
            'team_id' => auth()->user()->currentTeam->id ?? auth()->user()->currentTeam->id,
        ]));

        $material->addWeight($this->form->getState()['current_weight']);

        return $material;
    }
}
