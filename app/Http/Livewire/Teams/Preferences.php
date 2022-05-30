<?php

namespace App\Http\Livewire\Teams;

use App\Models\Team;
use Livewire\Component;

class Preferences extends Component
{
    public Team $team;

    protected $rules = [
        'team.settings.currency' => ['nullable'],
        'team.settings.unit' => ['nullable'],
        'team.settings.welcome' => ['nullable', 'boolean'],
    ];

    public function render()
    {
        return view('livewire.teams.preferences');
    }

    public function save()
    {
        $this->team->save();

        $this->emit('saved');
    }
}
