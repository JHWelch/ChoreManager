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

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/chores/create', \App\Http\Livewire\Chores\Save::class)
    ->name('chores.create');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/chores/{chore}', \App\Http\Livewire\Chores\Save::class)
    ->name('chores.edit');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/chores', \App\Http\Livewire\Chores\Index::class)
    ->name('chores.index');

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/chore_instances', \App\Http\Livewire\ChoreInstances\Index::class)
    ->name('chore_instances.index');
