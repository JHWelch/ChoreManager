<?php

namespace Tests;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Create a new user and act as them for the tests.
     *
     * @return
     */
    protected function testUser()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
        $user->switchTeam(Team::first());

        return $user;
    }
}
