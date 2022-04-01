<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
use App\Traits\WithDelete;
use App\Traits\WithSorting;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithDelete;
    use WithPagination;
    use WithSorting;

    public $perPage = 10;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function render()
    {
        return view('livewire.printers.table', [
            'rows' => $this->rows,
        ]);
    }

    public function getRowsProperty()
    {
        return Printer::forCurrentTeam()
            ->when($this->search !== '', fn($query) => $query
                ->where('name', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhere('model', 'LIKE', '%' . trim($this->search) . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }
}
