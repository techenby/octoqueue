<?php

namespace App\Http\Livewire\PrintJobs;

use App\Models\PrintJob;
use App\Traits\WithDelete;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithDelete;
    use WithPagination;

    public $perPage = 10;
    public $search;

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
            ->paginate($this->perPage);
    }
}
