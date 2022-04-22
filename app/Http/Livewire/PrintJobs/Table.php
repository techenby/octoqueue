<?php

namespace App\Http\Livewire\PrintJobs;

use App\Models\Printer;
use App\Models\PrintJob;
use App\Models\PrintJobType;
use App\Models\Spool;
use App\Traits\WithDelete;
use App\Traits\WithSorting;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class Table extends Component
{
    use WithDelete;
    use WithPagination;
    use WithSorting;

    public $perPage = 10;
    public $modalLabel = 'printer_id';
    public $modalType = 'printer_id';
    public $search = '';
    public $selected = [];
    public $selectAll = false;
    public $selectPage = false;
    public $setModal = false;
    public $setValue;
    public $sortField = 'name';
    public $sortDirection = 'asc';

    protected $listeners = ['refresh' => '$refresh'];

    public function updatedSelectPage($value)
    {
        $this->selected = ($value) ? $this->rows->pluck('id')->map(fn ($id) => (string) $id)->toArray() : [];
    }

    public function render()
    {
        return view('livewire.print-jobs.table')
            ->with([
                'isDisabled' => $this->isDisabled,
                'options' => $this->options,
                'rows' => $this->rows,
            ]);
    }

    public function getOptionsProperty()
    {
        return [
            'printer_id' => Printer::forCurrentTeam()->select('id', 'name')->pluck('name', 'id'),
            'job_type_id' => PrintJobType::forCurrentTeam()->select('id', 'name')->orderBy('priority')->pluck('name', 'id'),
            'color_hex' => Spool::forCurrentTeam()->select('color', 'color_hex')->pluck('color', 'color_hex'),
        ];
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

    public function getIsDisabledProperty()
    {
        return [
            'change-type' => empty($this->selected),
            'change-color' => empty($this->selected),
            'delete' => empty($this->selected),
            'set-printer' => empty($this->selected),
        ];
    }

    public function duplicate($id)
    {
        $this->rows->firstWhere('id', $id)->duplicate();

        $this->emit('refresh');
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

    public function massSet($column = null)
    {
        if ($column === null) {
            $column = $this->modalType;
        }

        $models = $this->rows->whereIn('id', $this->selected);

        $models->each(fn($model) => $model->update([$column => $this->setValue]));

        $this->emit('refresh');
        $this->resetModal();
    }

    public function resetModal()
    {
        $this->reset('modalLabel', 'modalType', 'setModal', 'setValue');
    }

    public function showSetModal($type)
    {
        $this->modalType = $type;
        $this->modalLabel = Str::of($type)->replace('_id', '')->replace('_', ' ')->ucfirst();
        $this->setModal = true;
    }
}
