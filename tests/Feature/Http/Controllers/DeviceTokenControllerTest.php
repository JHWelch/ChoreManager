<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\DeviceToken;
use Tests\TestCase;

class DeviceTokenControllerTest extends TestCase
{
    /** @test */
    public function user_can_register_a_device_token(): void
    {
        $this->testUser();
        $payload = ['token' => 'test-device-token'];

        $response = $this->postJson(route('api.device_tokens.store'), $payload);

        $response->assertCreated();
        $this->assertDatabaseHas('device_tokens', $payload);
    }

    /** @test */
    public function user_can_update_an_existing_token(): void
    {
        $this->testUser();
        $token = DeviceToken::factory([
            'updated_at' => '2021-01-01 00:00:00',
        ])->for($this->user)->create();

        $response = $this->postJson(route('api.device_tokens.store'), [
            'token' => $token->token,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('device_tokens', [
            'token' => $token->token,
            'user_id' => $this->user->id,
            'updated_at' => now(),
        ]);
    }

    /** @test */
    public function user_can_reassign_existing_token(): void
    {
        $this->testUser();
        $token = DeviceToken::factory()->create();

        $response = $this->postJson(route('api.device_tokens.store'), [
            'token' => $token->token,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('device_tokens', [
            'token' => $token->token,
            'user_id' => $this->user->id,
        ]);
    }
}
