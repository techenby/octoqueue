<?php

namespace App\Http\Livewire\Teams;

use App\Models\PrintType;
use Livewire\Component;

class PrintTypes extends Component
{
    public $listeners = ['refresh' => '$refresh'];

    public $editing;

    public $newType;

    public $rules = [
        'editing.name' => 'required',
    ];

    public function render()
    {
        return view('livewire.teams.print-types')
            ->with([
                'types' => $this->types,
            ]);
    }

    public function getTypesProperty()
    {
        return PrintType::forCurrentTeam()->orderBy('priority')->get();
    }

    public function edit($id)
    {
        $this->editing = $this->types->firstWhere('id', $id);
    }

    public function move($id, $direction)
    {
        $type = $this->types->firstWhere('id', $id);

        if ($direction === 'up' && $type->priority !== 1) {
            $number = $type->priority - 1;
            $otherType = $this->types->firstWhere('priority', $number);
        } elseif ($direction === 'down' && $type->priority !== $this->types->pluck('priority')->max()) {
            $number = $type->priority + 1;
            $otherType = $this->types->firstWhere('priority', $number);
        }

        if (isset($otherType)) {
            $otherType->priority = $type->priority;
            $otherType->save();

            $type->priority = $number;
            $type->save();
        }

        $this->emit('refresh');
    }

    public function saveNewType()
    {
        $this->validate([
            'newType' => 'required',
        ]);

        auth()->user()->currentTeam->printTypes()->create(['name' => $this->newType, 'priority' => $this->types->count() + 1]);
        $this->reset('newType');
        $this->emit('refresh');
    }

    public function save()
    {
        $this->validate();

        $this->editing->save();
        $this->reset('editing');
        $this->emit('refresh');
    }
}
