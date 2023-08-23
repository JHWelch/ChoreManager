<?php

namespace Tests\Feature\Http\Controllers;

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
}
