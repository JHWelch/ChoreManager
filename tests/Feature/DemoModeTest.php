<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class DemoModeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        (new DatabaseSeeder())->call(DemoSeeder::class);
        Config::set('demo.enabled', true);
    }

    protected function demoUser()
    {
        return User::where('email', 'demo@example.com')->first();
    }

    /** @test */
    public function when_demo_mode_is_enabled_user_does_not_need_to_log_in(): void
    {
        $response = $this->get(route('dashboard'));

        $this->assertEquals(Auth::id(), $this->demoUser()->id);
        $response->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function when_demo_mode_is_enabled_demo_banner_is_shown(): void
    {
        $response = $this->followingRedirects()->get(route('dashboard'));

        $response->assertSee('Chore Manager is running in demo mode. All changes will be reset nightly.');
    }

    /** @test */
    public function when_demo_mode_is_disabled_demo_banner_is_not_shown(): void
    {
        Config::set('demo.enabled', false);

        $response = $this->followingRedirects()->get(route('dashboard'));

        $response->assertDontSee('Chore Manager is running in demo mode. All changes will be reset nightly.');
    }
}
