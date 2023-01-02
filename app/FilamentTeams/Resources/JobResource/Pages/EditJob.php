<?php

namespace App\FilamentTeams\Resources\JobResource\Pages;

use App\FilamentTeams\Resources\JobResource;
use Artificertech\FilamentMultiContext\Concerns\ContextualPage;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditJob extends EditRecord
{
    use ContextualPage;

    protected static string $resource = JobResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
