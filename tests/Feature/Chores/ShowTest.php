<?php

use App\Livewire\Chores\Show;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;

use function Pest\Livewire\livewire;

it('can reach show page', function () {
    $chore = Chore::factory()->for($this->user()['user'])->create();

    $response = $this->get(route('chores.show', $chore));

    $response->assertOk();
});

test('user cannot view chores for another user', function () {
    $this->user();
    $chore = Chore::factory()->forUser()->create();

    $response = $this->get(route('chores.show', ['chore' => $chore]));

    $response->assertForbidden();
});

it('can see chore info on chores show', function () {
    $chore = Chore::factory([
        'title' => 'Walk the dog.',
        'description' => 'Do not forget the poop bags.',
        'frequency_id' => 1,
        'frequency_interval' => 2,
        'user_id' => $this->user()['user']->id,
    ])->create();

    $component = livewire(Show::class, ['chore' => $chore]);

    $component->assertSee('Walk the dog.');
    $component->assertSee('Do not forget the poop bags.');
    $component->assertSee('Repeats every 2 days');
});

it('can complete chore from chore page', function () {
    $this->user();
    $chore = Chore::factory()->for($this->user)->withFirstInstance()->create();
    $instance = $chore->nextChoreInstance;

    $component = livewire(Show::class, ['chore' => $chore])
        ->call('complete');

    $instance->refresh();
    expect($instance->is_completed)->toEqual(true);
    $component->assertRedirect('/');
});

it('can complete chore without first instance', function () {
    $this->user();
    $chore = Chore::factory()->for($this->user)->create();

    $component = livewire(Show::class, ['chore' => $chore])
        ->call('complete');

    $component->assertRedirect('/');
    $now = now()->toDateString();
    $choreInstance = ChoreInstance::first();
    expect($choreInstance)->not->toBeNull();
    expect($choreInstance->chore_id)->toEqual($chore->id);
    expect($choreInstance->is_completed)->toBeTrue();
    expect($choreInstance->due_date->toDateString())->toEqual($now);
    expect($choreInstance->completed_date->toDateString())->toEqual($now);
    expect($choreInstance->user_id)->toEqual($this->user->id);
    expect($choreInstance->completed_by_id)->toEqual($this->user->id);
});

it('can see chore history', function () {
    $user1 = $this->user()['user'];
    $user2 = User::factory()->create();
    $chore = Chore::factory()
        ->for($this->user)
        ->has(
            ChoreInstance::factory()->count(3)->sequence(
                [
                    'completed_date' => today()->subDays(1),
                    'user_id' => $user1->id,
                    'completed_by_id' => $user1->id,
                ],
                [
                    'completed_date' => today()->subDays(2),
                    'user_id' => $user2->id,
                    'completed_by_id' => $user2->id,
                ],
                [
                    'completed_date' => today()->subDays(3),
                    'user_id' => $user1->id,
                    'completed_by_id' => $user1->id,
                ],
            )
        )
        ->create();

    $component = livewire(Show::class, ['chore' => $chore]);

    $component->assertSeeInOrder([
        $user1->name,
        'completed chore',
        'yesterday',
        $user2->name,
        'completed chore',
        '2 days ago',
        $user1->name,
        'completed chore',
        '3 days ago',
    ]);
});

it('can see tooltip of exact date', function () {
    $user1 = $this->user()['user'];
    $user2 = User::factory()->create();
    $chore = Chore::factory()
        ->for($this->user)
        ->has(
            ChoreInstance::factory()->count(3)->sequence(
                [
                    'completed_date' => $date1 = today()->subDays(1),
                    'user_id' => $user1->id,
                    'completed_by_id' => $user1->id,
                ],
                [
                    'completed_date' => $date2 = today()->subDays(2),
                    'user_id' => $user2->id,
                    'completed_by_id' => $user2->id,
                ],
                [
                    'completed_date' => $date3 = today()->subDays(3),
                    'user_id' => $user1->id,
                    'completed_by_id' => $user1->id,
                ],
            )
        )
        ->create();

    $component = livewire(Show::class, ['chore' => $chore]);

    $component->assertSeeInOrder([
        $date1->format('m/d/Y'),
        $date2->format('m/d/Y'),
        $date3->format('m/d/Y'),
    ]);
});

test('chores assigned to team display team as owner', function () {
    $team = $this->user()['team'];
    $chore = Chore::factory([
        'title' => 'Walk the dog',
    ])
        ->assignedToTeam()
        ->for($team)
        ->create();

    $component = livewire(Show::class, ['chore' => $chore]);

    $component->assertSeeInOrder([
        'Owner',
        $team->name,
    ]);
});

test('when complete session flag is present show modal', function () {
    $user = $this->user()['user'];
    $chore = Chore::factory()->for($user)->create();
    session()->flash('complete', true);

    $component = livewire(Show::class, ['chore' => $chore]);

    $component->assertDispatched('openModal', 'chores.modals.custom-complete');
});

test('when complete session flag is not present dont show modal', function () {
    $user = $this->user()['user'];
    $chore = Chore::factory()->for($user)->create();

    $component = livewire(Show::class, ['chore' => $chore]);

    $component->assertNotDispatched('openModal', 'chores.modals.custom-complete');
});
