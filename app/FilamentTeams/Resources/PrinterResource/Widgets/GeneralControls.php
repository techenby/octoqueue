<?php

namespace App\FilamentTeams\Resources\PrinterResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class GeneralControls extends Widget
{
    public ?Model $record = null;

    protected static string $view = 'filament.resources.printer-resource.widgets.general-controls';
}
