<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PrintersController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\SpoolsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

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
        Route::get('/jobs/create', 'create')->name('jobs.create');
        Route::get('/jobs/{job}/edit', 'edit')->name('jobs.edit');
    });
});
