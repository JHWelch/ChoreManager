<?php

namespace Tests\Feature\Api\ChoreInstances;

use App\Models\Chore;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function can_get_upcoming_chores_with_their_due_dates(): void
    {
        $user   = $this->testUser()['user'];
        $chores = Chore::factory()
            ->count(3)
            ->for($user)
            ->withFirstInstance()
            ->create();
        $chores = $chores->sortBy(
            fn ($chore) => $chore->nextChoreInstance->due_date->timestamp
        )->values();

        $response = $this->get(route('api.chore_instances.index'));

        $response->assertJson([
            [
                'title'    => $chores[0]->title,
                'due_date' => $chores[0]->nextChoreInstance->due_date->toDateString(),
            ],
            [
                'title'    => $chores[1]->title,
                'due_date' => $chores[1]->nextChoreInstance->due_date->toDateString(),
            ],
            [
                'title'    => $chores[2]->title,
                'due_date' => $chores[2]->nextChoreInstance->due_date->toDateString(),
            ],
        ]);
    }
}
