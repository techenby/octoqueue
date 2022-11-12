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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/printers', App\Http\Livewire\Printers\Table::class)->name('printers');
    Route::get('/printers/create', App\Http\Livewire\Printers\Form::class)->name('printers.create');
    Route::get('/printers/{printer}/edit', App\Http\Livewire\Printers\Form::class)->name('printers.edit');

    Route::get('/filaments', App\Http\Livewire\Filaments\Table::class)->name('filaments');
    Route::get('/filaments/create', App\Http\Livewire\Filaments\Form::class)->name('filaments.create');
    Route::get('/filaments/{filament}/edit', App\Http\Livewire\Filaments\Form::class)->name('filaments.edit');
});
