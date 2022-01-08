<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function new_user_automatically_has_default_settings()
    {
        // Arrange
        // Create user
        $user = User::factory()->create();

        // Assert
        // Check if they have the correct default setting values
        $this->assertEquals($user->settings->has_daily_digest, false);
    }
}
