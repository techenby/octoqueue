<?php

namespace App\Http\Livewire\Bit;

use App\Models\Printer;
use Livewire\Component;

class PrinterFiles extends Component
{
    public Printer $printer;

    public $path;

    public function render()
    {
        return view('livewire.bit.printer-files', [
            'files' => $this->files,
        ]);
    }

    public function getFilesProperty()
    {
        return $this->printer->files();
    }

    public function select($path)
    {
        $this->path = $path;
        $this->emit('selectFile', [$this->printer->id, $path]);
    }
}
