<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintersController;
use App\Http\Controllers\SpoolsController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard')->with(['printers' => App\Models\Printer::forCurrentTeam()->get()]);
    })->name('dashboard');


    Route::controller(PrintersController::class)->group(function () {
        Route::get('/printers', 'index')->name('printers');
        Route::get('/printers/create', 'create')->name('printers.create');
        Route::get('/printers/{printer}/edit', 'edit')->name('printers.edit');
    });

    Route::controller(SpoolsController::class)->group(function () {
        Route::get('/spools', 'index')->name('spools');
        Route::get('/spools/create', 'create')->name('spools.create');
        Route::get('/spools/{spool}/edit', 'edit')->name('spools.edit');
    });

    Route::controller(QueueController::class)->group(function () {
        Route::get('/queue', 'index')->name('queue');
        Route::get('/queue/create', 'create')->name('queue.create');
        Route::get('/queue/{job}/edit', 'edit')->name('queue.edit');
    });
});
