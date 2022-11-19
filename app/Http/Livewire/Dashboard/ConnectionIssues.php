<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class ConnectionIssues extends Component
{
    public function render()
    {
        return view('livewire.dashboard.connection-issues')
            ->with([
                'printers' => auth()->user()->currentTeam->printers()->whereIn('status', ['offline', 'error'])->get(),
            ]);
    }
}
