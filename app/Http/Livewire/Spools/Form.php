<?php

namespace App\Http\Livewire\Spools;

use App\Calculator;
use App\Models\Spool;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Form extends Component
{
    public $spool;
    public $initialWeight;
    public $teamSettings;

    public function mount($spool = null)
    {
        if ($spool === null) {
            $this->spool = new Spool();
        } else {
            $this->spool = $spool;
        }

        $this->teamSettings = auth()->user()->currentTeam->settings;
    }

    public function updatedSpoolColorHex($hex)
    {
        $response = Http::get('https://www.thecolorapi.com/id', ['hex' => $hex]);

        if ($response->successful() && empty($this->spool->color)) {
            $this->spool->color = $response->json()['name']['value'];
        }
    }

    public function render()
    {
        return view('livewire.spools.form', [
            'materials' => $this->materials,
        ]);
    }

    public function getMaterialsProperty()
    {
        return array_keys((new Calculator())->materials);
    }

    public function save()
    {
        $this->validate();

        if ($this->spool->id === null) {
            $this->spool->addWeight($this->initialWeight, false);
        }
        $this->spool->team_id = auth()->user()->currentTeam->id;

        $this->spool->save();

        return redirect()->route('spools');
    }

    protected function rules()
    {
        $rules = [
            'spool.color' => ['nullable'],
            'spool.color_hex' => ['nullable'],
            'spool.brand' => ['nullable'],
            'spool.cost' => ['nullable'],
            'spool.material' => ['required'],
            'spool.diameter' => ['required'],
            'spool.empty' => ['required'],
        ];

        if ($this->spool->id === null) {
            $rules['initialWeight'] = ['required'];
        }

        return $rules;
    }
}
