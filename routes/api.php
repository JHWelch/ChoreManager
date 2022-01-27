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
Route::post('/token', [App\Http\Controllers\Api\AuthController::class, 'store'])
    ->name('api.token');

Route::middleware('auth:sanctum')
    ->name('api.')
    ->group(function () {
        Route::get('/auth_user', [\App\Http\Controllers\Api\AuthUserController::class, 'show'])
            ->name('auth_user.show');

        Route::apiResource('users', \App\Http\Controllers\Api\UserController::class)
            ->only(['show']);

        Route::get('/chores', [\App\Http\Controllers\Api\ChoreController::class, 'index'])
            ->name('chores.index');

        Route::get('/chore_instances', [\App\Http\Controllers\Api\ChoreInstanceController::class, 'index'])
            ->name('chore_instances.index');
    });
