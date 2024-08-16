<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

test('a user can view their own info', function () {
    $this->testUser();

    $response = $this->get(route(
        'api.users.show',
        ['user' => $this->user]
    ));

    $response->assertOk();
    $response->assertJson(['data' => [
        'id' => $this->user->id,
        'name' => $this->user->name,
        'email' => $this->user->email,
        'profile_photo_path' => $this->user->profile_photo_path,
        'current_team_id' => $this->user->current_team_id,
    ]]);
});

test('a user can view users from their team', function () {
    $this->testUser();
    $user = User::factory()
        ->hasAttached($this->team)
        ->create();

    $response = $this->get(route(
        'api.users.show',
        ['user' => $user]
    ));

    $response->assertOk();
    $response->assertJson(['data' => [
        'id' => $user->id,
        'name' => $user->name,
        'profile_photo_path' => $this->user->profile_photo_path,
    ]]);
    $response->assertJson(function (AssertableJson $json) {
        $json->missing('data.email')
            ->missing('data.current_team_id')
            ->etc();
    });
});

test('a user cannot view users not in their teams', function () {
    $this->testUser();
    $user = User::factory()
        ->hasAttached(Team::factory())
        ->create();

    $response = $this->get(route(
        'api.users.show',
        ['user' => $user]
    ));

    $response->assertForbidden();
});
