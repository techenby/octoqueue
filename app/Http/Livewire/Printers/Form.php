<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use App\Models\Spool;
use Livewire\Component;

class Form extends Component
{
    public $printer;

    protected $rules = [
        'printer.name' => ['required', 'string'],
        'printer.model' => ['nullable', 'string'],
        'printer.url' => ['required', 'url'],
        'printer.api_key' => ['required', 'string'],
        'printer.spool_id' => ['nullable'],
    ];

    public function mount($printer = null)
    {
        if ($printer === null) {
            $this->printer = new Printer();
        } else {
            $this->printer = $printer;
        }
    }

    public function render()
    {
        return view('livewire.printers.form')
            ->with([
                'spools' => $this->spools,
            ]);
    }

    public function getSpoolsProperty()
    {
        return Spool::forCurrentTeam()->get();
    }

    public function save()
    {
        $this->validate();

        if ($this->printer->status === 'Connection Error') {
            return $this->notify('error', 'Cannot connect to printer. Please check the URL and API Key.');
        }

        $this->printer->team_id = auth()->user()->currentTeam->id;
        $this->printer->save();

        return redirect()->route('printers');
    }
}
