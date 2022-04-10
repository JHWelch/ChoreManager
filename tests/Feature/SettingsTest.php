<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function new_user_automatically_has_default_settings()
    {
        $user = User::factory()->create();

        $this->assertEquals($user->settings->has_daily_digest, false);
    }
}
