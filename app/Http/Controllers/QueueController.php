<?php

namespace App\Http\Controllers;

use App\Models\PrintJob;
use Illuminate\Routing\Controller;

class QueueController extends Controller
{
    public function index()
    {
        return view('queue.index');
    }

    public function create()
    {
        return view('queue.create');
    }

    public function edit(PrintJob $job)
    {
        return view('queue.edit', [
            'job' => $job,
        ]);
    }
}
