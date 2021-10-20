<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function api_user_can_get_an_api_token()
    {
        // Arrange
        // Create a user
        $user = User::factory()->create();

        // Act
        // Call token API endpoint
        $response = $this->post(route('api.token'), [
            'email'       => $user->email,
            'password'    => 'password',
            'device_name' => 'Phone X 10',
        ]);

        // Assert
        // Token is created and returned
        $token = PersonalAccessToken::first();
        $this->assertEquals($user->id, $token->tokenable_id);
        [$id, $response_token] = explode('|', $response->baseResponse->content(), 2);
        $this->assertEquals(hash('sha256', $response_token), $token->token);
        $this->assertEquals('Phone X 10', $token->name);
    }
}
