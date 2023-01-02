<?php

namespace App\FilamentTeams\Resources\JobResource\Pages;

use App\FilamentTeams\Resources\JobResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateJob extends CreateRecord
{
    protected static string $resource = JobResource::class;
}
