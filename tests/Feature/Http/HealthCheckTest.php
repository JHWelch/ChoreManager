<?php

namespace Tests\Feature\Http;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    /** @test */
    public function health_check_returns_an_ok()
    {
        $response = $this->get(route('health-check'));

        $response->assertStatus(200);
    }
}
