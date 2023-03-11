<?php

use App\Http\Livewire\CalendarTokens\Index as CalendarTokensIndex;
use App\Http\Livewire\ChoreInstances\Index as ChoreInstancesIndex;
use App\Http\Livewire\Chores\Index as ChoresIndex;
use App\Http\Livewire\Chores\Save as ChoresSave;
use App\Http\Livewire\Chores\Show as ChoresShow;
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

Route::get('/health-check', fn () => response()->json(['status' => 'ok']))->name('health-check');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/', fn () => redirect(route('dashboard')));
    Route::get('/dashboard', ChoreInstancesIndex::class)->name('dashboard');

    Route::get('/chore_instances', ChoreInstancesIndex::class)->name('chore_instances.index');

    Route::get('/chores', ChoresIndex::class)->name('chores.index');
    Route::get('/chores/create', ChoresSave::class)->name('chores.create');
    Route::get('/chores/{chore}', ChoresShow::class)->name('chores.show');
    Route::get('/chores/{chore}/edit', ChoresSave::class)->name('chores.edit');

    Route::get('/calendar_links', CalendarTokensIndex::class)->name('calendar_tokens.index');
});
