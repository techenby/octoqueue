<?php

namespace App\Filament\Resources\PrinterResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class Camera extends Widget
{
    protected static string $view = 'filament.resources.printer-resource.widgets.printer-camera';

    public ?Model $record = null;
}
