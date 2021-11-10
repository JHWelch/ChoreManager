<?php

use App\Models\Chore;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


test('api user can get chore list', function () {
    // Arrange
    // Create user with chores
    $user = $this->testUser()['user'];
    Chore::factory()
        ->count(3)
        ->state(new Sequence(
            ['title' => 'Do dishes'],
            ['title' => 'Walk dog'],
            ['title' => 'Do laundry'],
        ))
        ->for($user)
        ->create();

    // Act
    // Call chore index endpoint
    $response = $this->get(route('api.chores.index'));

    // Assert
    // Chores return in expected format.
    $response->assertJson([
        ['title' => 'Do dishes'],
        ['title' => 'Walk dog'],
        ['title' => 'Do laundry'],
    ]);
});
