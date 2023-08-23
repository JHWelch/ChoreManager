<?php

namespace Tests\Feature\Models;

use App\Models\DeviceToken;
use App\Models\Team;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    private function adminTeam($user = null)
    {
        return Team::factory([
            'name'          => 'Admins',
            'personal_team' => false,
            'user_id'       => $user?->id ?? User::factory(),
        ]);
    }

    /** @test */
    public function isAdmin_returns_true_if_user_owns_admin_group(): void
    {
        $user = User::factory()->create();
        $this->adminTeam($user)->create();

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function isAdmin_returns_true_if_user_is_a_user_of_admin_team(): void
    {
        $user = User::factory()->create();
        $this->adminTeam()->hasAttached($user)->create();

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function isAdmin_returns_false_if_user_is_not_associated_with_admin_team(): void
    {
        $user = User::factory()->create();

        $this->assertFalse($user->isAdmin());
    }

    /** @test */
    public function isAdmin_caches_value(): void
    {
        $user       = User::factory()->create();
        $admin_team = $this->adminTeam()->hasAttached($user)->create();

        $this->assertTrue($user->isAdmin());

        $admin_team->users()->detach($user);

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function routeNotificationForFcm_returns_array_of_device_tokens(): void
    {
        $this->testUser();
        DeviceToken::factory()
            ->for($this->user)
            ->count(3)
            ->sequence([
                'token' => 'token1',
            ], [
                'token' => 'token2',
            ], [
                'token' => 'token3',
            ])
            ->create();
        $this->user->refresh();

        $this->assertEquals(
            ['token1', 'token2', 'token3'],
            $this->user->routeNotificationForFcm()
        );
    }
}
