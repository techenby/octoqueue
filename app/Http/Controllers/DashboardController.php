<?php

namespace App\Http\Controllers;

use App\Models\PrintJob;
use Illuminate\Support\Arr;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $currentTeam = auth()->user()->currentTeam->load('printers.currentJob');

        if (Arr::get($currentTeam->settings, 'welcome', 1)) {
            $completed = [
                'has_printers' => $currentTeam->printers->count() > 0,
                'has_spools' => $currentTeam->spools()->count() > 0,
                'has_jobs' => $currentTeam->jobs()->count() > 0,
                'has_types' => $currentTeam->jobTypes()->count() > 0,
            ];
        } else {
            $metrics = [
                'queued' => PrintJob::forCurrentTeam($currentTeam)->whereNull('started_at')->count(),
                'completed' => PrintJob::forCurrentTeam($currentTeam)->whereNotNull('completed_at')->count(),
                'filament' => PrintJob::forCurrentTeam($currentTeam)->whereNotNull('completed_at')->sum('filament_used'),
            ];
        }

        return view('dashboard', [
            'completed' => $completed ?? [],
            'metrics' => $metrics ?? [],
            'statuses' => $currentTeam->printers->groupBy('status'),
            'team' => $currentTeam,
        ]);
    }
}
