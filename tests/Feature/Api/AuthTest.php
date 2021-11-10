<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

test('api user can get an api token', function () {
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
});
