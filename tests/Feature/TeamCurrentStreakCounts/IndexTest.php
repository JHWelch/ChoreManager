<?php

use App\Models\StreakCount;
use App\Models\Team;

test('user can get current streak for their team', function () {
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
});

test('user cannot get streak for another team', function () {
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
});
