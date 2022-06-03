<?php

namespace App\Http\Livewire\Bit;

use App\Models\Printer as Model;
use Livewire\Component;

class Controls extends Component
{
    public Model $printer;

    public $amount = 10;

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
}
