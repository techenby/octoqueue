<?php

namespace App\Http\Livewire\Bit;

use App\Models\Printer as Model;
use Livewire\Component;
use TechEnby\OctoPrint\OctoPrint;

class Printer extends Component
{
    public Model $printer;

    public $loaded = false;

    protected $listeners = ['refresh' => '$refresh'];

    public function render()
    {
        return view('livewire.bit.printer')
            ->with([
                'bed' => $this->bed,
                'currentJob' => $this->currentJob,
                'currentJobStatus' => $this->currentJobStatus,
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

    public function getCurrentJobStatusProperty()
    {
        if ($this->status === 'Printing') {
            return (new OctoPrint($this->printer->url, $this->printer->api_key))->job();
        }
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

    public function completed()
    {
        $this->currentJob->completed();
        $this->emit('refresh');
    }

    public function print()
    {
        $this->nextJob->start($this->printer);
        $this->emit('refresh');
    }

    public function load()
    {
        $this->loaded = true;
    }

    public function stop()
    {
        $this->printer->cancel();

        if ($this->currentJob) {
            $this->currentJob->cancel();
        }
    }
}
