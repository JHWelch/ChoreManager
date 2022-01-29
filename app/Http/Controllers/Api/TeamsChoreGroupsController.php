<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Team;

class TeamsChoreGroupsController extends Controller
{
    public function index(Team $team)
    {
        $groups = $team
            ->choreInstances()
            ->notCompleted()
            ->dueTodayOrPast()
            ->orderBy('due_date')
            ->with('chore', 'user')
            ->get();

        return ['data' => $groups->mapToGroups(fn ($i) => $this->mapToGroups($i))];
    }

    protected function mapToGroups($instance)
    {
        if ($instance->due_date->startOfDay() < today()) {
            return ['past_due' => $this->mapChoreInstance($instance)];
        }

        return ['today' => $this->mapChoreInstance($instance)];
    }

    protected function mapChoreInstance($instance)
    {
        return [
            'title'    => $instance->chore->title,
            'due_date' => $instance->due_date->toDateString(),
            'owner'    => $instance->user->name,
        ];
    }
}
