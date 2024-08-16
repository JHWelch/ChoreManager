<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    actingAs($this->user);
});

test('current profile information is available', function () {
    livewire(UpdateProfileInformationForm::class)
        ->assertSet('state.name', $this->user->name)
        ->assertSet('state.email', $this->user->email);
});

test('profile information can be updated', function () {
    livewire(UpdateProfileInformationForm::class)
        ->set('state', ['name' => 'Test Name', 'email' => 'test@example.com'])
        ->call('updateProfileInformation');
    expect($this->user->fresh())
        ->name->toEqual('Test Name')
        ->email->toEqual('test@example.com');
});
