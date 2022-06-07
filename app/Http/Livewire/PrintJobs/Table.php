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

    public $filters = [
        'status' => [],
    ];
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
            ->when(
                in_array('started', $this->filters['status'])
                    && ! in_array('to-print', $this->filters['status'])
                    && ! in_array('completed', $this->filters['status']),
                fn ($query) => $query->whereNotNull('started_at')->whereNull('completed_at')
            )
            ->when(
                ! in_array('started', $this->filters['status'])
                    && in_array('to-print', $this->filters['status'])
                    && ! in_array('completed', $this->filters['status']),
                fn ($query) => $query->whereNull('started_at')->whereNull('completed_at')
            )
            ->when(
                ! in_array('started', $this->filters['status'])
                    && ! in_array('to-print', $this->filters['status'])
                    && in_array('completed', $this->filters['status']),
                fn ($query) => $query->whereNotNull('started_at')->whereNotNull('completed_at')
            )
            ->when(
                in_array('started', $this->filters['status'])
                    && in_array('to-print', $this->filters['status'])
                    && ! in_array('completed', $this->filters['status']),
                fn ($query) => $query->whereNull('completed_at')
            )
            ->when(
                in_array('started', $this->filters['status'])
                    && ! in_array('to-print', $this->filters['status'])
                    && in_array('completed', $this->filters['status']),
                fn ($query) => $query->whereNotNull('started_at')
            )
            ->when(
                ! in_array('started', $this->filters['status'])
                    && in_array('to-print', $this->filters['status'])
                    && in_array('completed', $this->filters['status']),
                function ($query) {
                    $query->where(fn ($query) => $query->whereNull('started_at')->whereNull('completed_at'))
                        ->orWhere(fn ($query) => $query->whereNotNull('started_at')->whereNotNull('completed_at'));
                }
            )
            ->when($this->search, fn ($query) => $query
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
            'mark-as-completed' => empty($this->selected),
        ];
    }

    public function duplicate($id)
    {
        $this->rows->firstWhere('id', $id)->duplicate();

        $this->emit('refresh');
    }

    public function edit($id)
    {
        return redirect()->route('jobs.edit', $id);
    }

    public function print($id)
    {
        $job = $this->rows->firstWhere('id', $id);

        if ($job->printer_id) {
            $printer = $job->printer;
        } else {
            $printers = Printer::whereIn('id', $job->files->keys())
                ->whereStatus('Operational')
                ->whereIn('spool_id', $job->availableSpools()->select('id')->pluck('id'))
                ->get();

            if ($printers->isEmpty()) {
                return $this->notify('error', 'No printer is available and loaded with the required filament.');
            }

            $printer = $printers->first();
        }

        if ($printer->status === 'Printing') {
            return $this->notify('error', $printer->name . ' is already printing');
        }

        try {
            $job->start($printer);
            return $this->notify('success', 'Job Started');
        } catch (\Exception $e) {
            return $this->notify('exception', "Whoops, looks like there was an issue.\n" . ' Printer: ' . $printer->name . "\n" . ' File: ' . $job->files[$printer->id] . "\n" . ' Error message: ' . json_decode($e->getMessage(), true)['error'] ?? $e->getMessage());
        }
    }

    public function markAsCompleted($id = null)
    {
        $models = $this->rows->whereIn('id', $this->selected);

        if ($models->filter(fn ($job) => $job->printer_id === null)->isNotEmpty()) {
            return $this->notify('error', 'Please set printer prior to marking as completed');
        }

        if ($models->filter(fn ($job) => $job->color_hex === null)->isNotEmpty()) {
            return $this->notify('error', 'Please set color prior to marking as completed');
        }

        if ($modelsWithoutSpool = $models->filter(fn ($job) => $job->spool_id)) {
            $modelsWithoutSpool->each(fn ($model) => $model->update(['spool_id' => Spool::forCurrentTeam()->firstWhere('color_hex', $model->color_hex)->id]));
        }

        $models->each(fn ($model) => $model->completed());

        $this->emit('refresh');
    }

    public function massSet($column = null)
    {
        if ($column === null) {
            $column = $this->modalType;
        }

        $models = $this->rows->whereIn('id', $this->selected);

        $models->each(fn ($model) => $model->update([$column => $this->setValue]));

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

    public function toggleStatus($type)
    {
        if (in_array($type, $this->filters['status'])) {
            $this->filters['status'] = array_diff($this->filters['status'], [$type]);
        } else {
            $this->filters['status'][] = $type;
        }
    }
}
