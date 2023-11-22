<?php

namespace Tests\Feature;

use Tests\TestCase;

class PingTest extends TestCase
{
    /** @test */
    public function it_pongs_to_your_ping(): void
    {
        $this->get(route('ping'))
            ->assertOk()
            ->assertSee('pong');
    }
}
