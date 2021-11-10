<?php

use App\Http\Livewire\Chores\Show;
use App\Models\Chore;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

test('user can delete chore from show', function () {
    // Arrange
    // Create a chore
    $chore = Chore::factory()->for($this->testUser()['user'])->create();

    // Act
    // Navigate to chore and delete it
    $component = Livewire::test(Show::class, ['chore' => $chore])
        ->call('delete');

    // Assert
    // Chore is no longer in database
    $this->assertDatabaseCount((new Chore)->getTable(), 0);
    $component->assertRedirect('/');
});
