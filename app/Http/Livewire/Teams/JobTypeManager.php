<?php

namespace App\Http\Livewire\Teams;

use App\Models\PrintJobType;
use Livewire\Component;

class JobTypeManager extends Component
{
    public $listeners = ['refresh' => '$refresh'];

    public $newType;

    public function render()
    {
        return view('livewire.teams.job-type-manager')
            ->with([
                'types' => $this->types,
            ]);
    }

    public function getTypesProperty()
    {
        return PrintJobType::forCurrentTeam()->orderBy('priority')->get();
    }

    public function saveNewType()
    {
        auth()->user()->currentTeam->jobTypes()->create(['name' => $this->newType, 'priority' => $this->types->count() + 1]);
        $this->reset('newType');
        $this->emit('refresh');
    }
}
