<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ical/{token}', [\App\Http\Controllers\Api\ICalendarController::class, 'show'])
    ->name('icalendar.show');

/*
* MOBILE API
*/
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/chores', [\App\Http\Controllers\Api\ChoreController::class, 'index'])
        ->name('api.chores.index');
});
