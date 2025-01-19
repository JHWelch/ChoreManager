<?php

use App\Models\DeviceToken;
use App\Models\Team;
use App\Models\User;

function adminTeam($user = null)
{
    return Team::factory([
        'name' => 'Admins',
        'personal_team' => false,
        'user_id' => $user?->id ?? User::factory(),
    ]);
}

test('is admin returns true if user owns admin group', function () {
    $user = User::factory()->create();
    adminTeam($user)->create();

    expect($user->isAdmin())->toBeTrue();
});

test('is admin returns true if user is a user of admin team', function () {
    $user = User::factory()->create();
    adminTeam()->hasAttached($user)->create();

    expect($user->isAdmin())->toBeTrue();
});

test('is admin returns false if user is not associated with admin team', function () {
    $user = User::factory()->create();

    expect($user->isAdmin())->toBeFalse();
});

test('is admin caches value', function () {
    $user = User::factory()->create();
    $admin_team = adminTeam()->hasAttached($user)->create();

    expect($user->isAdmin())->toBeTrue();

    $admin_team->users()->detach($user);

    expect($user->isAdmin())->toBeTrue();
});

test('route notification for fcm returns array of device tokens', function () {
    $this->user();
    DeviceToken::factory()
        ->for($this->user)
        ->count(3)
        ->sequence([
            'token' => 'token1',
        ], [
            'token' => 'token2',
        ], [
            'token' => 'token3',
        ])
        ->create();
    $this->user->refresh();

    expect($this->user->routeNotificationForFcm())->toEqual(['token1', 'token2', 'token3']);
});
