<?php

namespace App\Filament\Resources\TeamResource\Pages;

use App\Filament\Resources\TeamResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTeam extends EditRecord
{
    protected static string $resource = TeamResource::class;

    protected static ?string $title = 'Team Settings';

    protected static string $view = 'teams.show';

    protected function getActions(): array
    {
        return [
            //
        ];
    }
}
