<?php

use App\Models\Chore;

test('can get upcoming chores with their due dates', function () {
    // Arrange
    // Create user with chores
    $user   = $this->testUser()['user'];
    $chores = Chore::factory()
        ->count(3)
        ->for($user)
        ->withFirstInstance()
        ->create();

    $chores = $chores->sortBy(
        fn ($chore) => $chore->nextChoreInstance->due_date->timestamp
    )->values();

    // Act
    // Call chore instance index endpoint
    $response = $this->get(route('api.chore_instances.index'));

    // Assert
    // Json returned with chore titles and due dates
    $response->assertJson([
        [
            'title'    => $chores[0]->title,
            'due_date' => $chores[0]->nextChoreInstance->due_date->toDateString(),
        ],
        [
            'title'    => $chores[1]->title,
            'due_date' => $chores[1]->nextChoreInstance->due_date->toDateString(),
        ],
        [
            'title'    => $chores[2]->title,
            'due_date' => $chores[2]->nextChoreInstance->due_date->toDateString(),
        ],
    ]);
});
