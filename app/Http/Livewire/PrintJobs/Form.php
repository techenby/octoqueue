<?php

namespace App\Http\Livewire\PrintJobs;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintJobType;
use App\Models\Spool;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class Form extends Component
{
    use WithFileUploads;

    public $files = [];
    public $job;
    public $quantity = 1;
    public $showUploadModal = false;

    public $gcode;
    public $uploadPath;
    public $uploadPrinter;

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
        return Spool::forCurrentTeam()->select('color', 'color_hex')->distinct()->get()->pluck('color', 'color_hex');
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

    public function resetUploadModal()
    {
        $this->reset('gcode', 'showUploadModal', 'uploadPath', 'uploadPrinter');
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

    public function showUpload($printerId)
    {
        if (! isset($this->files[$printerId])) {
            return $this->notify('error', 'Where do you want to upload the file? Please select a sibling file and click upload again.');
        }


        $this->showUploadModal = true;
        $this->uploadPath = $this->getPathFromSelected($this->files[$printerId]);
        $this->uploadPrinter = $printerId;
    }

    public function uploadFile()
    {
        $this->validate([
            'gcode' => ['required', 'max:12000'],
            'uploadPath' => ['required'],
        ]);

        $filename = $this->uploadPath . '/' . $this->gcode->getClientOriginalName() ?? basename($this->uploadPath);
        $this->printers->find($this->uploadPrinter)->upload($filename, $this->gcode->get());

        $this->emit('notify', ['message' => 'Uploaded file to OctoPi', 'type' => 'success']);

        $this->selectFile([$this->uploadPrinter, $filename]);

        $this->resetUploadModal();
    }

    private function getPathFromSelected($string)
    {
        if(Str::endsWith($string, '.gcode')) {
            return Str::replace(basename($string), '', $string);
        }

        return Str::finish($string, '/');
    }
}
