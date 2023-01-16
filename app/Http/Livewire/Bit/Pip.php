<?php

namespace App\Http\Livewire\Bit;

use App\Models\Printer;
use Livewire\Component;

class Pip extends Component
{
    public $printer;

    public $showModal = false;

    protected $listeners = ['pip' => 'show'];

    public function render()
    {
        return view('livewire.bit.pip');
    }

    public function show($id)
    {
        $this->printer = Printer::find($id);
        $this->showModal = true;
    }

    public function resetModal()
    {
        $this->reset('printer', 'showModal');
    }
}
