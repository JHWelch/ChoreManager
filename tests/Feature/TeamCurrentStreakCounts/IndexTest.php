<?php

namespace Tests\Feature\TeamCurrentStreakCounts;

use App\Models\StreakCount;
use App\Models\Team;
use Tests\TestCase;

class IndexTest extends TestCase
{
    /** @test */
    public function user_can_get_current_streak_for_their_team(): void
    {
        $this->testUser();
        $streak = StreakCount::factory()->for($this->team)->create();

        $response = $this->get(
            route(
                'api.team_current_streak.index',
                ['team' => $this->team]
            )
        );

        $response->assertOk();
        $response->assertJson([
            'data' => [
                'id' => $streak->id,
                'count' => $streak->count,
                'team_id' => $streak->team_id,
            ],
        ]);
    }

    /** @test */
    public function user_cannot_get_streak_for_another_team(): void
    {
        $this->testUser();
        $other_team = Team::factory()->create();
        StreakCount::factory()->for($other_team)->create();

        $response = $this->get(
            route(
                'api.team_current_streak.index',
                ['team' => $other_team]
            )
        );

        $response->assertForbidden();
    }
}
