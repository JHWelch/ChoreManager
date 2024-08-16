<?php

use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

test('api user can get an api token', function () {
    $user = User::factory()->create();

    $response = $this->post(route('api.token'), [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'Phone X 10',
    ]);

    $token = PersonalAccessToken::first();
    expect($token->tokenable_id)->toEqual($user->id);
    [$id, $response_token] = explode('|', $response->json('token'), 2);
    expect($token->token)->toEqual(hash('sha256', $response_token));
    expect($token->name)->toEqual('Phone X 10');
});

test('api user will return user', function () {
    $user = User::factory()->create();

    $response = $this->post(route('api.token'), [
        'email' => $user->email,
        'password' => 'password',
        'device_name' => 'Phone X 10',
    ]);

    $response->assertJson(['user' => [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'profile_photo_path' => $user->profile_photo_path,
        'current_team_id' => $user->current_team_id,
    ]]);
});
