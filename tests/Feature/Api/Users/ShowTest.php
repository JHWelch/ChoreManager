<?php

namespace Tests\Feature\Api\Users;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function a_user_can_view_their_own_info(): void
    {
        $this->testUser();

        $response = $this->get(route(
            'api.users.show',
            ['user' => $this->user]
        ));

        $response->assertOk();
        $response->assertJson(['data' => [
            'id'                 => $this->user->id,
            'name'               => $this->user->name,
            'email'              => $this->user->email,
            'profile_photo_path' => $this->user->profile_photo_path,
            'current_team_id'    => $this->user->current_team_id,
        ]]);
    }

    /** @test */
    public function a_user_can_view_users_from_their_team(): void
    {
        $this->testUser();
        $user = User::factory()
            ->hasAttached($this->team)
            ->create();

        $response = $this->get(route(
            'api.users.show',
            ['user' => $user]
        ));

        $response->assertOk();
        $response->assertJson(['data' => [
            'id'                 => $user->id,
            'name'               => $user->name,
            'profile_photo_path' => $this->user->profile_photo_path,
        ]]);
        $response->assertJson(function (AssertableJson $json) {
            $json->missing('data.email')
                ->missing('data.current_team_id')
                ->etc();
        });
    }

    /** @test */
    public function a_user_cannot_view_users_not_in_their_teams(): void
    {
        $this->testUser();
        $user = User::factory()
            ->hasAttached(Team::factory())
            ->create();

        $response = $this->get(route(
            'api.users.show',
            ['user' => $user]
        ));

        $response->assertForbidden();
    }
}
