<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', App\Http\Controllers\MarketingController::class)->name('marketing');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/queue', App\Http\Livewire\Jobs\Table::class)->name('queue');
    Route::get('/jobs/create', App\Http\Livewire\Jobs\Form::class)->name('jobs.create');
    Route::get('/jobs/{job}/edit', App\Http\Livewire\Jobs\Form::class)->name('jobs.edit');

    Route::get('/printers', App\Http\Livewire\Printers\Table::class)->name('printers');
    Route::get('/printers/create', App\Http\Livewire\Printers\Form::class)->name('printers.create');
    Route::get('/printers/{printer}/edit', App\Http\Livewire\Printers\Form::class)->name('printers.edit');

    Route::get('/materials', App\Http\Livewire\Materials\Table::class)->name('materials');
    Route::get('/materials/create', App\Http\Livewire\Materials\Form::class)->name('materials.create');
    Route::get('/materials/{material}/edit', App\Http\Livewire\Materials\Form::class)->name('materials.edit');
});
