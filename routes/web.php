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

Route::get('/', [App\Http\Controllers\MarketingController::class, 'welcome'])->name('welcome');
Route::get('/marketing', [App\Http\Controllers\MarketingController::class, 'marketing'])->name('marketing');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', App\Http\Livewire\Dashboard::class)->name('dashboard');
});
