<?php

namespace Tests\Feature\TeamCurrentStreakCounts;

use App\Models\StreakCount;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function user_can_get_current_streak_for_their_team()
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
                'id'      => $streak->id,
                'count'   => $streak->count,
                'team_id' => $streak->team_id,
            ],
        ]);
    }
}
