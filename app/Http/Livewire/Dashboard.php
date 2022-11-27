<?php

namespace App\Http\Livewire;

use App\Jobs\FetchPrinterStatus;
use App\Models\Job;
use App\Models\Tool;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard')
            ->with([
                'connectionIssues' => $this->connectionIssues,
                'currentlyPrinting' => $this->currentlyPrinting,
                'missingMaterials' => $this->missingMaterials,
                'queuedJobs' => $this->queuedJobs,
                'standby' => $this->standby,
            ]);
    }

    public function getConnectionIssuesProperty()
    {
        return $this->printers->whereIn('status', ['offline', 'error']);
    }

    public function getCurrentlyPrintingProperty()
    {
        return $this->printers->whereIn('status', ['printing', 'paused', 'pausing']);
    }

    public function getMissingMaterialsProperty()
    {
        return Tool::whereIn('printer_id', $this->printers->pluck('id'))
            ->whereNull('material_id')
            ->get()
            ->map(function ($tool) {
                $tool->printer = $this->printers->firstWhere('id', $tool->printer_id);
                return $tool;
            });
    }

    public function getPrintersProperty()
    {
        return auth()->user()->currentTeam->printers;
    }

    public function getQueuedJobsProperty()
    {
        return auth()->user()->currentTeam->jobs()
            ->join('print_types', 'jobs.print_type_id', '=', 'print_types.id')
            ->select('jobs.*', 'print_types.priority as print_type_priority')
            ->whereNull('jobs.started_at')
            ->orderBy('print_type_priority')
            ->limit(5)
            ->get();
    }

    public function getStandbyProperty()
    {
        return $this->printers->whereIn('status', ['operational']);
    }

    public function fetchStatus($id)
    {
        FetchPrinterStatus::dispatch($this->printers->find($id));
    }

    public function pause($id)
    {
        $printer = $this->printers->find($id);

        if ($printer->status === 'printing') {
            $printer->pause();
        }
    }

    public function resume($id)
    {
        $printer = $this->printers->find($id);

        if (in_array($printer->status, ['paused', 'pausing'])) {
            $printer->resume();
        }
    }

    public function save($id)
    {
        $this->printers->find($id)->saveCurrentlyPrinting();
    }

    public function stop($id)
    {
        $printer = $this->printers->find($id);

        if (in_array($printer->status, ['printing', 'printing'])) {
            $printer->cancel();
        }
    }
}
