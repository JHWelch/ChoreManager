<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChoreInstance;
use App\Models\Team;
use Illuminate\Support\Collection;

class TeamsChoreGroupsController extends Controller
{
    /**
     * Get the chore groups for the team.
     *
     * @param Team $team
     * @return array<string, Collection<string, Collection<int, array<string, mixed>>>>
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

        return ['data' => $groups->mapToGroups(fn ($i) => $this->mapToGroups($i))];
    }

    /**
     * Map the chore instance to a group.
     *
     * @param ChoreInstance $instance
     * @return array<string, array<string, mixed>>
     */
    protected function mapToGroups($instance)
    {
        if ($instance->due_date->startOfDay() < today()) {
            return ['past_due' => $this->mapChoreInstance($instance)];
        }

        return ['today' => $this->mapChoreInstance($instance)];
    }

    /**
     * Map the chore instance to an array.
     *
     * @param ChoreInstance $instance
     * @return array<string, mixed>
     */
    protected function mapChoreInstance(ChoreInstance $instance): array
    {
        return [
            'title'    => $instance->chore->title,
            'due_date' => $instance->due_date->toDateString(),
            'owner'    => $instance->user->name,
        ];
    }
}
