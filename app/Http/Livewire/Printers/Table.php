<?php

namespace App\Http\Livewire\Printers;

use App\Models\Printer;
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

    public $currentPrinter;
    public $currentSpool;
    public $perPage = 10;
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $spoolModal = false;

    public function render()
    {
        return view('livewire.printers.table', [
            'rows' => $this->rows,
            'spools' => $this->spools,
        ]);
    }

    public function getRowsProperty()
    {
        return Printer::forCurrentTeam()
            ->when($this->search !== '', fn($query) => $query
                ->where('name', 'LIKE', '%' . trim($this->search) . '%')
                ->orWhere('model', 'LIKE', '%' . trim($this->search) . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getSpoolsProperty()
    {
        return Spool::all();
    }

    public function updateSpool()
    {
        $this->currentPrinter->spool_id = $this->currentSpool;
        $this->currentPrinter->save();

        $this->resetSpoolModal();
    }

    public function resetSpoolModal()
    {
        $this->reset('currentPrinter', 'currentSpool', 'spoolModal');
    }

    public function showSpoolModal($id)
    {
        $this->currentPrinter = $this->rows->find($id);
        $this->currentSpool = $this->currentPrinter->spool_id;
        $this->spoolModal = true;
    }
}
