<?php

namespace App\Http\Livewire\Bit;

use App\Models\Printer as Model;
use Livewire\Component;

class Printer extends Component
{
    public Model $printer;

    public $loaded = false;

    public function render()
    {
        return view('livewire.bit.printer')
            ->with([
                'bed' => $this->bed,
                'currentJob' => $this->currentJob,
                'hotend' => $this->hotend,
                'nextJob' => $this->nextJob,
                'status' => $this->status,
            ]);
    }

    public function getBedProperty()
    {
        return ! $this->loaded ? 'Connecting' : $this->hardwareTemps['bed']['actual'] . '℃ / ' . $this->hardwareTemps['bed']['target'] . '℃';
    }

    public function getCurrentJobProperty()
    {
        return $this->printer->currentJob;
    }

    public function getHardwareTempsProperty()
    {
        return $this->printer->hardwareState->temperature;
    }

    public function getHotendProperty()
    {
        return ! $this->loaded ? 'Connecting' : $this->hardwareTemps['tool0']['actual'] . '℃ / ' . $this->hardwareTemps['tool0']['target'] . '℃';
    }

    public function getNextJobProperty()
    {
        return $this->printer->nextJob;
    }

    public function getStatusProperty()
    {
        return $this->loaded ? $this->printer->status : 'Connecting';
    }

    public function load()
    {
        $this->loaded = true;
    }
}
