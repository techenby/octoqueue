<?php

namespace App\Filament\Resources\MaterialResource\Pages;

use App\Filament\Resources\MaterialResource;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\ForceDeleteAction;
use Filament\Pages\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditMaterial extends EditRecord
{
    protected static string $resource = MaterialResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        if ($data['current_weight'] !== null) {
            $record->addWeight($data['current_weight']);
            $this->data['current_weight'] = null;
            $this->emit('$refresh');
        }

        return $record;
    }
}
