<?php

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Livewire\Livewire;

test('admin can see index page', function () {
    $this->adminTestUser();

    $response = $this->get(UserResource::getUrl('index'));

    $response->assertSuccessful();
});

test('standard user cannot see index page', function () {
    $this->testUser();

    $response = $this->get(UserResource::getUrl('index'));

    $response->assertForbidden();
});

test('can see user fields', function () {
    $this->adminTestUser();
    $user = User::factory()->create();

    Livewire::test(ListUsers::class)
        ->assertSee($user->name)
        ->assertSee($user->created_at->format('M j, Y H:i:s'))
        ->assertSee($user->updated_at->format('M j, Y H:i:s'))
        ->assertSee($user->email);
});
