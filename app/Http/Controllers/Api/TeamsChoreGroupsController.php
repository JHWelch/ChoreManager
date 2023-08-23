<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChoreInstance;
use App\Models\Team;

class TeamsChoreGroupsController extends Controller
{
    /**
     * Get the chore groups for the team.
     *
     * @return array<string, array<string, array<string, string>>>
     */
    public function index(Team $team): array
    {
        $groups = $team
            ->choreInstances()
            ->notCompleted()
            ->dueTodayOrPast()
            ->orderBy('due_date')
            ->with('chore', 'user')
            ->get();

        return ['data' => $groups->mapToGroups(fn ($i) => $this->mapToGroups($i))->toArray()];
    }

    /**
     * Map the chore instance to a group.
     *
     * @return array<string, array<string, string>>
     */
    protected function mapToGroups(ChoreInstance $instance): array
    {
        if ($instance->due_date->startOfDay() < today()) {
            return ['past_due' => $this->mapChoreInstance($instance)];
        }

        return ['today' => $this->mapChoreInstance($instance)];
    }

    /**
     * Map the chore instance to an array.
     *
     * @return array<string, string>
     */
    protected function mapChoreInstance(ChoreInstance $instance): array
    {
        return [
            'title' => $instance->chore->title,
            'due_date' => $instance->due_date->toDateString(),
            'owner' => $instance->user->name,
        ];
    }
}
