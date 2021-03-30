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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', fn () => redirect(route('dashboard')));

    Route::get('/dashboard', \App\Http\Livewire\ChoreInstances\Index::class)
        ->name('dashboard');

    Route::get('/chores', \App\Http\Livewire\Chores\Index::class)
        ->name('chores.index');

    Route::get('/chores/create', \App\Http\Livewire\Chores\Save::class)
        ->name('chores.create');

    Route::get('/chores/{chore}/edit', \App\Http\Livewire\Chores\Save::class)
        ->name('chores.edit');

    Route::get('/chores/{chore}', \App\Http\Livewire\Chores\Show::class)
        ->name('chores.show');

    Route::get('/chore_instances', \App\Http\Livewire\ChoreInstances\Index::class)
        ->name('chore_instances.index');

    Route::get('/calendar_links', \App\Http\Livewire\CalendarTokens\Index::class)
        ->name('calendar_tokens.index');
});
