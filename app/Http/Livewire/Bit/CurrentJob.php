<?php

namespace App\Http\Livewire\Bit;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintJobType;
use Livewire\Component;
use TechEnby\OctoPrint\OctoPrint;

class CurrentJob extends Component
{
    public Printer $printer;

    public function render()
    {
        return view('livewire.bit.current-job')
            ->with([
                'elapsed' => $this->elapsed,
                'label' => $this->label,
                'progress' => $this->progress,
                'currentJob' => $this->printer->currentJob,
            ]);
    }

    public function getElapsedProperty()
    {
        return $this->printer->currentJob->started_at ?? now()->subSeconds($this->job->progress['printTime']);
    }

    public function getJobProperty()
    {
        return (new OctoPrint($this->printer->url, $this->printer->api_key))->job();
    }

    public function getLabelProperty()
    {
        return $this->printer->currentJob->name ?? $this->job->job['file']['display'];
    }

    public function getProgressProperty()
    {
        return round($this->job->progress['completion'], 2) . '%';
    }

    public function completed()
    {
        $this->printer->currentJob->completed();
    }

    public function save()
    {
        PrintJob::create([
            'name' => $this->label,
            'team_id' => $this->printer->team_id,
            'job_type_id' => PrintJobType::forCurrentTeam()->get('id')->first()->id,
            'printer_id' => $this->printer->id,
            'spool_id' => $this->printer->spool_id,
            'user_id' => auth()->id(),
            'color_hex' => $this->printer->spool->color_hex,
            'files' => [
                $this->printer->id => $this->job->job['file']['path'],
            ],
            'started_at' => $this->elapsed,
        ]);

        $this->emit('$refresh');
        $this->notify('success', 'Saved job');
    }
}
