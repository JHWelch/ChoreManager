<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class DemoModeTest extends TestCase
{
    use RefreshDatabase;

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
    public function when_demo_mode_is_on_user_does_not_need_to_log_in()
    {
        // Act
        // Try to access page behind login
        $response = $this->get(route('dashboard'));

        // Assert
        // Demo user is logged in, and page can be accessed.
        $this->assertEquals(Auth::id(), $this->demoUser()->id);
        $response->assertRedirect(route('dashboard'));
    }
}
