<?php

namespace App\Http\Controllers;

use App\Models\Spool;
use Illuminate\Routing\Controller;

class SpoolsController extends Controller
{
    public function index()
    {
        return view('spools.index', [
            'spools' => Spool::forCurrentTeam()->get(),
        ]);
    }

    public function create()
    {
        return view('spools.create');
    }

    public function edit(Spool $spool)
    {
        return view('spools.edit', [
            'spool' => $spool,
        ]);
    }
}
