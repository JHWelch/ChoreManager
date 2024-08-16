<?php

use App\Models\Team;
use Database\Seeders\AdminTeamSeeder;

test('admin team returns null if team not seeded', function () {
    $admin_team = Team::adminTeam();

    expect($admin_team)->toBeNull();
});

test('admin team returns team named admins', function () {
    $this->seed(AdminTeamSeeder::class);

    $admin_team = Team::adminTeam();

    expect($admin_team->name)->toEqual('Admins');
});

test('admin team cached for subsequent calls', function () {
    $this->seed(AdminTeamSeeder::class);

    $admin_team = Team::adminTeam();

    expect($admin_team)->not->toBeNull();

    Team::where('name', 'Admins')->delete();

    $admin_team = Team::adminTeam();

    expect($admin_team)->not->toBeNull();
});
