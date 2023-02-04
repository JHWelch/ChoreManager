<?php

namespace Tests\Feature\Models;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function tearDown(): void
    {
        parent::tearDown();
        Team::$admin_team = null;
    }

    private function adminTeam($user = null)
    {
        return Team::factory([
            'name'          => 'Admins',
            'personal_team' => false,
            'user_id'       => $user?->id ?? User::factory(),
        ]);
    }

    /** @test */
    public function isAdmin_returns_true_if_user_owns_admin_group()
    {
        $user = User::factory()->create();
        $this->adminTeam($user)->create();

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function isAdmin_returns_true_if_user_is_a_user_of_admin_team()
    {
        $user = User::factory()->create();
        $this->adminTeam()->hasAttached($user)->create();

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function isAdmin_returns_false_if_user_is_not_associated_with_admin_team()
    {
        $user = User::factory()->create();

        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function isAdmin_caches_value()
    {
        $user       = User::factory()->create();
        $admin_team = $this->adminTeam()->hasAttached($user)->create();

        $this->assertTrue($user->isAdmin());

        $admin_team->users()->detach($user);

        $this->assertTrue($user->isAdmin());
    }
}
