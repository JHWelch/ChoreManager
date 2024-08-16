<?php

use App\Models\DeviceToken;

use function Pest\Laravel\assertDatabaseHas;

test('user can register a device token', function () {
    $this->testUser();
    $payload = ['token' => 'test-device-token'];

    $response = $this->postJson(route('api.device_tokens.store'), $payload);

    $response->assertCreated();
    assertDatabaseHas('device_tokens', $payload);
});

test('user can update an existing token', function () {
    $this->testUser();
    $token = DeviceToken::factory([
        'updated_at' => '2021-01-01 00:00:00',
    ])->for($this->user)->create();

    $response = $this->postJson(route('api.device_tokens.store'), [
        'token' => $token->token,
    ]);

    $response->assertOk();
    assertDatabaseHas('device_tokens', [
        'token' => $token->token,
        'user_id' => $this->user->id,
        'updated_at' => now(),
    ]);
});

test('user can reassign existing token', function () {
    $this->testUser();
    $token = DeviceToken::factory()->create();

    $response = $this->postJson(route('api.device_tokens.store'), [
        'token' => $token->token,
    ]);

    $response->assertOk();
    assertDatabaseHas('device_tokens', [
        'token' => $token->token,
        'user_id' => $this->user->id,
    ]);
});

test('user can save two tokens', function () {
    $this->testUser();
    $token = DeviceToken::factory()->for($this->user)->create();

    $response = $this->postJson(route('api.device_tokens.store'), [
        'token' => $secondToken = 'second_token',
    ]);

    $response->assertCreated();
    assertDatabaseHas('device_tokens', [
        'token' => $token->token,
        'user_id' => $this->user->id,
    ]);
    assertDatabaseHas('device_tokens', [
        'token' => $secondToken,
        'user_id' => $this->user->id,
    ]);
});
