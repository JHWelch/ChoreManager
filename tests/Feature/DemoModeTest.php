<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class DemoModeTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function setUp(): void
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
    public function when_demo_mode_is_enabled_user_does_not_need_to_log_in()
    {
        // Act
        // Try to access page behind login
        $response = $this->get(route('dashboard'));

        // Assert
        // Demo user is logged in, and page can be accessed.
        $this->assertEquals(Auth::id(), $this->demoUser()->id);
        $response->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function when_demo_mode_is_enabled_demo_banner_is_shown()
    {
        // Act
        // Navigate to dashboard
        $response = $this->followingRedirects()->get(route('dashboard'));

        // Assert
        // The banner is diplayed
        $response->assertSee('Chore Manager is running in demo mode. All changes will be reset nightly.');
    }

    /** @test */
    public function when_demo_mode_is_disabled_demo_banner_is_not_shown()
    {
        // Arrange
        // Turn demo mode off
        Config::set('demo.enabled', false);

        // Act
        // Navigate to dashboard
        $response = $this->followingRedirects()->get(route('dashboard'));

        // Assert
        // The banner is diplayed
        $response->assertDontSee('Chore Manager is running in demo mode. All changes will be reset nightly.');
    }
}
