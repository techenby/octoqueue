<?php

namespace App\Http\Livewire\PrintJobs;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintJobType;
use App\Models\Spool;
use Livewire\Component;

class Form extends Component
{
    public $files = [];
    public $job;
    public $quantity = 1;

    protected $listeners = ['selectFile'];

    protected $rules = [
        'job.name' => ['required', 'string'],
        'job.color_hex' => ['nullable', 'string'],
        'job.job_type_id' => ['required'],
        'job.notes' => ['nullable', 'string'],
    ];

    public function mount($job = null)
    {
        if ($job === null) {
            $this->job = new PrintJob();
        } else {
            $this->job = $job;
        }
    }

    public function render()
    {
        return view('livewire.print-jobs.form')
            ->with([
                'colors' => $this->colors,
                'printers' => $this->printers,
                'types' => $this->types,
            ]);
    }

    public function getColorsProperty()
    {
        return Spool::forCurrentTeam()->select('color')->distinct()->get()->pluck('color');
    }

    public function getPrintersProperty()
    {
        return Printer::forCurrentTeam()->get();
    }

    public function getTypesProperty()
    {
        return PrintJobType::forCurrentTeam()->get();
    }

    public function adjustQuantity($direction)
    {
        if ($direction === 'add') {
            $this->quantity++;
        } elseif ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function save()
    {
        $this->validate();
        $this->processing = true;

        $this->job->team_id = auth()->user()->currentTeam->id;
        $this->job->user_id = auth()->id();
        $this->job->files = $this->files;
        $this->job->filament_used = 0;

        if (count($this->files) === 1) {
            $this->job->printer_id = $this->printers->where('name', ucfirst(array_key_first($this->files)))->first()->id;
        }

        $this->job->save();

        if ($this->quantity > 1) {
            foreach (range(2, $this->quantity) as $index) {
                $job = $this->job->replicate();
                $job->save();
            }
        }

        $this->processing = false;
        $this->emit('notify', ['message' => 'Added print jobs to queue', 'type' => 'success']);

        $this->redirect(route('dashboard'));
    }

    public function selectFile($data)
    {
        $label = strtolower($data[0]);
        $this->files[$label] = $data[1];
    }
}
