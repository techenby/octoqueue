<?php

namespace App\FilamentTeams\Resources\PrinterResource\Pages;

use App\FilamentTeams\Resources\PrinterResource;
use Artificertech\FilamentMultiContext\Concerns\ContextualPage;
use Filament\Resources\Pages\CreateRecord;

class CreatePrinter extends CreateRecord
{
    use ContextualPage;

    protected static string $resource = PrinterResource::class;
}
