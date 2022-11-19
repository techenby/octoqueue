<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard')
            ->with([
                'connectionIssues' => $this->connectionIssues,
                'currentlyPrinting' => $this->currentlyPrinting,
            ]);
    }

    public function getConnectionIssuesProperty()
    {
        return $this->printers->whereIn('status', ['offline', 'error']);
    }

    public function getCurrentlyPrintingProperty()
    {
        return $this->printers->whereIn('status', ['printing', 'paused']);
    }

    public function getPrintersProperty()
    {
        return auth()->user()->currentTeam->printers;
    }
}
