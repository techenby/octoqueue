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
    return view('welcome', [
        'features' => [
            ['label' => 'Print Monitoring', 'description' => "All of your receipts organized into one place, as long as you don't mind typing in the data by hand.", 'image' => asset('images/feature-1-ui-light.webp')],
            ['label' => 'Smart Queue', 'description' => "All of your receipts organized into one place, as long as you don't mind typing in the data by hand.", 'image' => asset('images/feature-2-ui-light.webp')],
            ['label' => 'Manage Materials', 'description' => "All of your receipts organized into one place, as long as you don't mind typing in the data by hand.", 'image' => asset('images/feature-3-ui-light.webp')],
            ['label' => 'Print Monitoring', 'description' => "All of your receipts organized into one place, as long as you don't mind typing in the data by hand.", 'image' => asset('images/feature-4-ui-light.webp')],
        ],
        'secondary' => [
            ['label' => 'Print Queue', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
            ['label' => 'Customizable Job Priority', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
            ['label' => 'Manage Materials', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
            ['label' => 'Printer Management', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
            ['label' => 'Print Monitoring', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
            ['label' => 'Queue History', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
            ['label' => 'Smart Alerts', 'description' => 'Tempor tellus in aliquet eu et sit nulla tellus. Suspendisse est, molestie blandit quis ac. Lacus.'],
        ],
    ]);
});

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
