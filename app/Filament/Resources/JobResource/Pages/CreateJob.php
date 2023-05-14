<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use App\Models\Job;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;

    public function getColorOptionsProperty()
    {
        return auth()->user()->currentTeam->materials->pluck('name', 'color_hex');
    }

    public function getPrintersProperty()
    {
        return auth()->user()->currentTeam->printers;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $model = static::getModel()::create(array_merge($data, [
            'type' => Job::class,
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
}
