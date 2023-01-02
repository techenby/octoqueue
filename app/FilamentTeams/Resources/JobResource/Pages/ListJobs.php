<?php

namespace App\FilamentTeams\Resources\JobResource\Pages;

use App\FilamentTeams\Resources\JobResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
