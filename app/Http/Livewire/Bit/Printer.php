<?php

namespace App\Http\Livewire\Bit;

use App\Models\Printer as Model;
use Exception;
use Livewire\Component;
use TechEnby\OctoPrint\OctoPrint;

class Printer extends Component
{
    public Model $printer;

    public $accessible = null;
    public $loaded = false;
    public $options = [
        'current-job' => 'Current Job',
        'next-job' => 'Next Job',
        'controls' => 'Controls',
    ];

    public $amount = 10;
    public $tab;
    public $temperature;

    protected $listeners = ['refresh' => '$refresh'];

    public function mount()
    {
        $this->determineTab();
    }

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

    public function getIsAccessibleProperty() {
        if ($this->accessible === null) {
            $this->accessible = isUrlAccessible($this->printer->url);
        }

        return $this->accessible;
    }

    public function getBedProperty()
    {
        return ! $this->loaded && ! $this->isAccessible ? 'Connecting' : $this->hardwareTemps['bed']['actual'] . '℃ / ' . $this->hardwareTemps['bed']['target'] . '℃';
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
        try {
            return $this->printer->hardwareState->temperature;
        } catch (Exception $e) {
            $this->notify('error', $this->printer->name . ' is not available');
            $this->accessible = false;
            return [
                'bed' => [
                    'actual' => 'X',
                    'target' => 'X',
                ],
                'tool0' => [
                    'actual' => 'X',
                    'target' => 'X',
                ],
            ];
        }
    }

    public function getHotendProperty()
    {
        return ! $this->loaded && ! $this->isAccessible ? 'Connecting' : $this->hardwareTemps['tool0']['actual'] . '℃ / ' . $this->hardwareTemps['tool0']['target'] . '℃';
    }

    public function getNextJobProperty()
    {
        return $this->printer->nextJob;
    }

    public function getStatusProperty()
    {
        return $this->loaded && $this->isAccessible ? $this->printer->status : 'Connecting';
    }

    public function completed()
    {
        $this->currentJob->completed();
        $this->emit('refresh');
    }

    public function home($axis)
    {
        $this->printer->client->home($axis);
    }

    public function jog($axis, $direction = '')
    {
        $value = $direction . $this->amount;

        if ($axis === 'x') {
            $this->printer->client->jog((int) $value, 0, 0);
        } elseif ($axis === 'y') {
            $this->printer->client->jog(0, (int) $value, 0);
        } elseif ($axis === 'z') {
            $this->printer->client->jog(0, 0, (int) $value);
        }
    }

    public function print()
    {
        $this->nextJob->start($this->printer);
        $this->emit('refresh');
    }

    public function load()
    {
        if($this->isAccessible) {
            $this->loaded = true;
        }
    }

    public function stop()
    {
        $this->printer->cancel();

        if ($this->currentJob) {
            $this->currentJob->cancel();
        }
    }

    public function tool($command)
    {
        if ($command === 'extrude' || $command === 'retract') {
            if ($this->printer->hardwareState->temperature['tool0']['actual'] < 180) {
                return $this->notify('error', 'Hotend is not warmed up, please wait until the temperature is greature than 180.');
            }

            $this->printer->client->$command($this->amount);
        } elseif ($command === 'temperature') {
            $this->printer->client->targetToolTemps(['tool0' => (int) $this->temperature]);
            $this->reset('temperature');
        } else {
            $this->notify('error', 'Could not find command for: ' . $command);
        }
    }

    private function determineTab()
    {
        $this->tab = $this->printer->status === 'Printing' ? 'current-job' : 'next-job';
    }
}
