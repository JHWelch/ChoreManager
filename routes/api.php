<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AuthUserController;
use App\Http\Controllers\Api\ChoreController;
use App\Http\Controllers\Api\ChoreInstanceController;
use App\Http\Controllers\Api\ICalendarController;
use App\Http\Controllers\Api\TeamCurrentStreakCountController;
use App\Http\Controllers\Api\TeamsChoreGroupsController;
use App\Http\Controllers\Api\UserController;
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

Route::get('/ical/{token}', [ICalendarController::class, 'show'])->name('icalendar.show');

/*
* MOBILE API
*/
Route::post('/token', [AuthController::class, 'store'])->name('api.token');

Route::middleware('auth:sanctum')
    ->name('api.')
    ->group(function () {
        Route::get('/auth_user', [AuthUserController::class, 'show'])->name('auth_user.show');

        Route::apiResource('users', UserController::class)->only(['show']);
        Route::apiResource('chores', ChoreController::class)->only(['index', 'update']);
        Route::apiResource('chore_instances', ChoreInstanceController::class)->only(['index']);

        Route::get('/teams/{team}/chore_groups', [TeamsChoreGroupsController::class, 'index'])
            ->name('teams.chore_groups.index')
            ->can('view', 'team');

        Route::get('/teams/{team}/current_streak', [TeamCurrentStreakCountController::class, 'index'])
            ->name('team_current_streak.index')
            ->can('view', 'team');
    });
