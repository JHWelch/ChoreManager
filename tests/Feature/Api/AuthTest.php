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
    expect($token->tokenable_id)->toEqual($user->id);
    [$id, $response_token] = explode('|', $response->baseResponse->content(), 2);
    expect($token->token)->toEqual(hash('sha256', $response_token));
    expect($token->name)->toEqual('Phone X 10');
});
