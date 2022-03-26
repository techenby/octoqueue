<?php

namespace App\Http\Livewire\PrintJobs;

use App\Models\PrintJob;
use App\Models\PrintJobType;
use App\Models\Spool;
use Livewire\Component;

class Form extends Component
{
    protected $listeners = ['selectFile'];

    public $job;
    public $quantity = 1;

    protected $rules = [
        'job.name' => ['required', 'string'],
        'job.color' => ['nullable', 'string'],
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
                'types' => $this->types,
            ]);
    }

    public function getColorsProperty()
    {
        return Spool::forCurrentTeam()->select('color')->distinct()->get()->pluck('color');
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

    public function selectFile($data)
    {
        $label = strtolower($data[0]);
        $this->files[$label] = $data[1];
    }
}
