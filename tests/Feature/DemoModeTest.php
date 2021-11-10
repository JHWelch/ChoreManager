<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

beforeEach(function () {
    (new DatabaseSeeder())->call(DemoSeeder::class);
    Config::set('demo.enabled', true);
});

test('when demo mode is enabled user does not need to log in', function () {
    // Act
    // Try to access page behind login
    $response = $this->get(route('dashboard'));

    // Assert
    // Demo user is logged in, and page can be accessed.
    expect(demoUser()->id)->toEqual(Auth::id());
    $response->assertRedirect(route('dashboard'));
});

test('when demo mode is enabled demo banner is shown', function () {
    // Act
    // Navigate to dashboard
    $response = $this->followingRedirects()->get(route('dashboard'));

    // Assert
    // The banner is diplayed
    $response->assertSee('Chore Manager is running in demo mode. All changes will be reset nightly.');
});

test('when demo mode is disabled demo banner is not shown', function () {
    // Arrange
    // Turn demo mode off
    Config::set('demo.enabled', false);

    // Act
    // Navigate to dashboard
    $response = $this->followingRedirects()->get(route('dashboard'));

    // Assert
    // The banner is diplayed
    $response->assertDontSee('Chore Manager is running in demo mode. All changes will be reset nightly.');
});

// Helpers
function demoUser()
{
    return User::where('email', 'demo@example.com')->first();
}
