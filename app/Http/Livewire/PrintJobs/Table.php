<?php

namespace App\Http\Livewire\PrintJobs;

use App\Models\Printer;
use App\Models\PrintJob;
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
            ->when($this->search, fn($query) => $query
                ->where('name', 'LIKE', '%' . trim($this->search) . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getSpoolsProperty()
    {
        return Spool::forCurrentTeam()->create();
    }

    public function print($id)
    {
        $job = $this->rows->firstWhere('id', $id);

        if ($job->printer_id) {
            $job->start($job->printer);
        } else {
            $printers = Printer::whereIn('id', $job->files->keys())->whereIn('spool_id', $job->availableSpools()->select('id')->pluck('id'))->get();

            if ($printers->count() === 1) {
                $job->start($printers->first());
            }
            // dd($printers);
        }
    }
}
