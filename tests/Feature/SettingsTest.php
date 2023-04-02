<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    /** @test */
    public function new_user_automatically_has_default_settings(): void
    {
        $user = User::factory()->create();

        $this->assertEquals($user->settings->has_daily_digest, false);
    }
}
