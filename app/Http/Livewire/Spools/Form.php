<?php

namespace App\Http\Livewire\Spools;

use App\Models\Spool;
use Livewire\Component;

class Form extends Component
{
    public $spool;
    public $initialWeight;

    protected $rules = [
        'spool.color_hex' => ['nullable'],
        'spool.brand' => ['nullable'],
        'spool.cost' => ['nullable'],
        'spool.material' => ['required'],
        'spool.diameter' => ['required'],
        'initialWeight' => ['required'],
    ];

    public function mount($spool = null)
    {
        if ($spool === null) {
            $this->spool = new Spool();
        } else {
            $this->spool = $spool;
        }
    }

    public function render()
    {
        return view('livewire.spools.form');
    }
}
