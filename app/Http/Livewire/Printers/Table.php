<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
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
        return view('livewire.printers.table', [
            'rows' => $this->rows
        ]);
    }

    public function getRowsProperty()
    {
        return Printer::forCurrentTeam()
            ->when($this->search !== '', fn($query) => $query
                ->where('name', 'LIKE', '%'.$this->search.'%')
                ->orWhere('model', 'LIKE', '%'.$this->search.'%'))
            ->paginate($this->perPage);
    }
}
