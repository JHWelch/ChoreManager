<?php

namespace Tests\Feature\Api\ChoreGroups;

use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class IndexText extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function returns_chores_grouped_by_today_and_past_due_for_team()
    {
        $this->testUser();
        $chores = ChoreInstance::factory()
            ->for(Chore::factory()->for($this->team))
            ->sequence(
                ['due_date' => today()->subDays(2)],
                ['due_date' => today()->subDay()],
                ['due_date' => today()],
            )
            ->count(3)
            ->create();

        $response = $this->get(
            route('api.teams.chore_groups.index', ['team' => $this->team]
        ));

        $response->assertJson(['data' => [
            'past_due' => [
                [
                    'due_date' => $chores[0]->due_date->toDateString(),
                    'title'    => $chores[0]->chore->title,
                    'owner'    => $chores[0]->user->name,
                ],
                [
                    'due_date' => $chores[1]->due_date->toDateString(),
                    'title'    => $chores[1]->chore->title,
                    'owner'    => $chores[1]->user->name,
                ],
            ],
            'today' => [
                [
                    'due_date' => $chores[2]->due_date->toDateString(),
                    'title'    => $chores[2]->chore->title,
                    'owner'    => $chores[2]->user->name,
                ],
            ],
        ]]);
    }
}
