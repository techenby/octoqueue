<?php

namespace App\Http\Livewire\Spools;

use App\Models\Spool;
use App\Traits\WithDelete;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithDelete;
    use WithPagination;

    public $perPage = 10;
    public $search = '';

    public function render()
    {
        return view('livewire.spools.table', [
            'rows' => $this->rows,
        ]);
    }

    public function getRowsProperty()
    {
        return Spool::forCurrentTeam()
            ->paginate($this->perPage);
    }
}
