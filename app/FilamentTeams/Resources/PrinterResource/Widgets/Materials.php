<?php

namespace App\FilamentTeams\Resources\PrinterResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class Materials extends Widget
{
    public ?Model $record = null;

    protected static string $view = 'filament.resources.printer-resource.widgets.materials';
}
