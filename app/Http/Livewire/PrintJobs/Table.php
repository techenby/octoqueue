<?php

namespace App\Http\Livewire\PrintJobs;

use App\Models\PrintJob;
use Livewire\Component;

class Table extends Component
{
    public function render()
    {
        return view('livewire.print-jobs.table')
            ->with([
                'jobs' => $this->jobs,
            ]);
    }

    public function getJobsProperty()
    {
        return PrintJob::forCurrentTeam()->get();
    }
}
