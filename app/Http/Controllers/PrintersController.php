<?php

namespace App\Http\Controllers;

use App\Models\Printer;
use App\Models\Spool;
use Illuminate\Routing\Controller;

class PrintersController extends Controller
{
    public function index()
    {
        return view('printers.index', [
            'printers' => Printer::forCurrentTeam()->get(),
        ]);
    }

    public function create()
    {
        return view('printers.create', [
            'spools' => Spool::forCurrentTeam()->get(),
        ]);
    }

    public function edit(Printer $printer)
    {
        return view('printers.edit', [
            'printer' => $printer,
        ]);
    }
}
