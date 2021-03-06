<?php

namespace Tests;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected User $user;
    protected Team $team;

    /**
     * Create a new user and act as them for the tests.
     *
     * @return
     */
    protected function testUser($attributes = [])
    {
        $this->actingAs($user = User::factory($attributes)->withPersonalTeam()->create());
        $user->switchTeam($team = Team::first());

        $this->user = $user;
        $this->team = $team;

        return [
            'user' => $user,
            'team' => $team,
        ];
    }
}
