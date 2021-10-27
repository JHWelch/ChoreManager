<?php

namespace Tests;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Carbon;

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

    /**
     * Travel to 2021-03-01, a known monday.
     *
     * @return void
     */
    protected function travelToKnownMonday()
    {
        $this->travelTo(Carbon::parse('2021-03-01'));
    }

    /**
     * Known Saturday (2021-03-06) after the known monday.
     *
     * @return Carbon
     */
    protected function knownSaturday()
    {
        return Carbon::parse('2021-03-06');
    }
}
