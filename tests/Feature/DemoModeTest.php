<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\DemoSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

beforeEach(function () {
    (new DatabaseSeeder)->call(DemoSeeder::class);
    Config::set('demo.enabled', true);
});

function demoUser()
{
    return User::where('email', 'demo@example.com')->first();
}

test('when demo mode is enabled user does not need to log in', function () {
    $response = $this->get(route('dashboard'));

    expect(demoUser()->id)->toEqual(Auth::id());
    $response->assertRedirect(route('dashboard'));
});

test('when demo mode is enabled demo banner is shown', function () {
    $response = $this->followingRedirects()->get(route('dashboard'));

    $response->assertSee('Chore Manager is running in demo mode. All changes will be reset nightly.');
});

test('when demo mode is disabled demo banner is not shown', function () {
    Config::set('demo.enabled', false);

    $response = $this->followingRedirects()->get(route('dashboard'));

    $response->assertDontSee('Chore Manager is running in demo mode. All changes will be reset nightly.');
});
