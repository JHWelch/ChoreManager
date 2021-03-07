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

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', \App\Http\Livewire\ChoreInstances\Index::class)
        ->name('dashboard');

    Route::get('/chores/create', \App\Http\Livewire\Chores\Save::class)
        ->name('chores.create');

    Route::get('/chores/{chore}', \App\Http\Livewire\Chores\Save::class)
        ->name('chores.edit');

    Route::get('/chores', \App\Http\Livewire\Chores\Index::class)
        ->name('chores.index');

    Route::get('/chore_instances', \App\Http\Livewire\ChoreInstances\Index::class)
        ->name('chore_instances.index');
});
