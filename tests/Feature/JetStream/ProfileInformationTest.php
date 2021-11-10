<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

test('current profile information is available', function () {
    $this->actingAs($user = User::factory()->create());

    $component = Livewire::test(UpdateProfileInformationForm::class);

    $this->assertEquals($user->name, $component->state['name']);
    $this->assertEquals($user->email, $component->state['email']);
});

test('profile information can be updated', function () {
    $this->actingAs($user = User::factory()->create());

    Livewire::test(UpdateProfileInformationForm::class)
            ->set('state', ['name' => 'Test Name', 'email' => 'test@example.com'])
            ->call('updateProfileInformation');

    $this->assertEquals('Test Name', $user->fresh()->name);
    $this->assertEquals('test@example.com', $user->fresh()->email);
});
