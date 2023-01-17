<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $model = static::getModel()::create(array_merge($data, [
            'team_id' => auth()->user()->currentTeam->id,
            'user_id' => auth()->id(),
        ]));

        if ($data['quantity'] > 1) {
            foreach (range(2, $data['quantity']) as $index) {
                $job = $model->replicate();
                $job->save();
            }
        }

        return $model;
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
