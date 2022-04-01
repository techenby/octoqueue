<?php

namespace App\Http\Livewire\PrintJobs;

use App\Models\PrintJob;
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

    protected $listeners = ['refresh' => '$refresh'];

    public function render()
    {
        return view('livewire.print-jobs.table')
            ->with([
                'rows' => $this->rows,
            ]);
    }

    public function getRowsProperty()
    {
        return PrintJob::forCurrentTeam()
            ->when($this->search, fn($query) => $query->where('name', 'LIKE', '%'.trim($this->search).'%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function print()
    {

    }
}
