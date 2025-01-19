<?php

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;

use function Pest\Livewire\livewire;

test('admin can see index page', function () {
    $this->adminUser();

    $response = $this->get(UserResource::getUrl('index'));

    $response->assertSuccessful();
});

test('standard user cannot see index page', function () {
    $this->user();

    $response = $this->get(UserResource::getUrl('index'));

    $response->assertForbidden();
});

it('can see user fields', function () {
    $this->adminUser();
    $user = User::factory()->create();

    livewire(ListUsers::class)
        ->assertSee($user->name)
        ->assertSee($user->created_at->format('M j, Y H:i:s'))
        ->assertSee($user->updated_at->format('M j, Y H:i:s'))
        ->assertSee($user->email);
});
