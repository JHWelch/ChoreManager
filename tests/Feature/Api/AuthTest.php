<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function api_user_can_get_an_api_token()
    {
        $user = User::factory()->create();

        $response = $this->post(route('api.token'), [
            'email'       => $user->email,
            'password'    => 'password',
            'device_name' => 'Phone X 10',
        ]);

        $token = PersonalAccessToken::first();
        $this->assertEquals($user->id, $token->tokenable_id);
        [$id, $response_token] = explode('|', $response->json('token'), 2);
        $this->assertEquals(hash('sha256', $response_token), $token->token);
        $this->assertEquals('Phone X 10', $token->name);
    }

    /** @test */
    public function api_user_will_return_user()
    {
        $user = User::factory()->create();

        $response = $this->post(route('api.token'), [
            'email'       => $user->email,
            'password'    => 'password',
            'device_name' => 'Phone X 10',
        ]);

        $response->assertJson(['user' => [
            'id'                 => $user->id,
            'name'               => $user->name,
            'email'              => $user->email,
            'profile_photo_path' => $user->profile_photo_path,
            'current_team_id'    => $user->current_team_id,
        ]]);
    }
}
