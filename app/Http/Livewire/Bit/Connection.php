<?php

namespace App\Http\Livewire\Bit;

use App\Models\Printer;
use Livewire\Component;

class Connection extends Component
{
    public Printer $printer;

    public $form;
    public $options;

    public function mount() {
        $connection = $this->printer->client->connection();
        $this->form = $connection->current;
        $this->options = $connection->options;
    }

    public function connect()
    {
        $this->printer->client->connect();
        $this->printer->update(['status' => $this->printer->client->state()]);
    }
}
