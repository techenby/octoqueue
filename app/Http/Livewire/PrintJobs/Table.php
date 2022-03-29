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

    public function render()
    {
        return view('livewire.print-jobs.table')
            ->with([
                'rows' => $this->rows,
            ]);
    }

    public function getRowsProperty()
    {
        return PrintJob::forCurrentTeam()->paginate();
    }
}
