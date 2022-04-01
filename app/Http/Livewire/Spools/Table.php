<?php

namespace App\Http\Livewire\Spools;

use App\Models\Spool;
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
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public function render()
    {
        return view('livewire.spools.table', [
            'rows' => $this->rows,
        ]);
    }

    public function getRowsProperty()
    {
        return Spool::forCurrentTeam()
            ->when($this->search, fn($query) => $query
                ->where('brand', 'LIKE', '%'.trim($this->search).'%')
                ->orWhere('color', 'LIKE', '%'.trim($this->search).'%')
                ->orWhere('color_hex', 'LIKE', '%'.trim($this->search).'%')
                ->orWhere('cost', 'LIKE', '%'.trim($this->search).'%')
                ->orWhere('material', 'LIKE', '%'.trim($this->search).'%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }
}
