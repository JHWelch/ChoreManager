<?php

use App\Http\Controllers\ChoreCompleteController;
use App\Livewire\CalendarTokens;
use App\Livewire\ChoreInstances;
use App\Livewire\Chores;
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
    Route::get('/dashboard', ChoreInstances\Index::class)->name('dashboard');

    Route::get('/chore_instances', ChoreInstances\Index::class)->name('chore_instances.index');

    Route::get('/chores', Chores\Index::class)->name('chores.index');
    Route::get('/chores/create', Chores\Save::class)->name('chores.create');
    Route::get('/chores/{chore}', Chores\Show::class)->name('chores.show');
    Route::get('/chores/{chore}/edit', Chores\Save::class)->name('chores.edit');

    Route::resource('chores.complete', ChoreCompleteController::class)->only(['index']);

    Route::get('/calendar_links', CalendarTokens\Index::class)->name('calendar_tokens.index');
});

Route::get('/ping', fn () => 'pong')->name('ping');
