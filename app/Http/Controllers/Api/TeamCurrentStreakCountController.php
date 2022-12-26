<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StreakCountResource;
use App\Models\Team;

class TeamCurrentStreakCountController extends Controller
{
    public function index(Team $team): StreakCountResource
    {
        return StreakCountResource::make($team->currentStreak);
    }
}
