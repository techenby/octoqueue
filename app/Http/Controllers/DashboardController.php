<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $currentTeam = auth()->user()->currentTeam->load('printers');

        $completed = [
            'has_printers' => $currentTeam->printers->count() > 0,
            'has_spools' => $currentTeam->spools()->count() > 0,
            'has_jobs' => $currentTeam->jobs()->count() > 0,
            'has_types' => $currentTeam->jobTypes()->count() > 0,
        ];

        return view('dashboard', [
            'printers' => $currentTeam->printers,
            'completed' => $completed,
            'showWelcome' => in_array(false, $completed),
            'team' => $currentTeam,
        ]);
    }
}
