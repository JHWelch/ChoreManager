<?php

namespace Tests\Feature\Http;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function health_check_returns_an_ok()
    {
        $response = $this->get(route('health-check'));

        $response->assertStatus(200);
    }
}
