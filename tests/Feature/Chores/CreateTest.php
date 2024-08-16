<?php

use App\Enums\FrequencyType;
use App\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

test('chore edit page can be reached', function () {
    $this->testUser();

    $response = $this->get(route('chores.create'));

    $response->assertOk();
});

it('can create chore', function () {
    $user = $this->testUser()['user'];

    livewire(Save::class)
        ->set('form.title', 'Do dishes')
        ->set('form.description', 'Do the dishes every night.')
        ->set('form.frequency_id', FrequencyType::daily->value)
        ->call('save');

    assertDatabaseHas((new Chore)->getTable(), [
        'title' => 'Do dishes',
        'description' => 'Do the dishes every night.',
        'frequency_id' => FrequencyType::daily,
        'user_id' => $user->id,
    ]);
});

test('a user can assign a chore to another team member', function () {
    $users = User::factory()->count(2)->hasTeams()->create();
    $team = Team::first();
    $assigned_user = $users->pop();
    $chore = Chore::factory()->raw();
    $this->actingAs($users->first());
    $users->first()->switchTeam($team);

    livewire(Save::class)
        ->set('form.title', $chore['title'])
        ->set('form.description', $chore['description'])
        ->set('form.frequency_id', $chore['frequency_id']->value)
        ->set('form.chore_user_id', $assigned_user->id)
        ->set('form.due_date', null)
        ->call('save');

    assertDatabaseHas((new Chore)->getTable(), [
        'user_id' => $assigned_user->id,
        'title' => $chore['title'],
        'description' => $chore['description'],
        'frequency_id' => $chore['frequency_id'],
    ]);
});

test('a chore can be assigned to a team', function () {
    $this->testUser();
    $chore = Chore::factory()->raw();

    $component = livewire(Save::class)
        ->set('form.title', $chore['title'])
        ->set('form.description', $chore['description'])
        ->set('form.frequency_id', $chore['frequency_id']->value)
        ->set('form.chore_user_id', null)
        ->call('save');

    $component->assertHasNoErrors();
    assertDatabaseHas((new Chore)->getTable(), [
        'title' => $chore['title'],
        'description' => $chore['description'],
        'frequency_id' => $chore['frequency_id'],
        'user_id' => null,
    ]);
});

test('chores assigned to team with due date create instance assigned to team member', function () {
    $user = $this->testUser()['user'];
    $chore = Chore::factory()->raw();
    $due_date = today()->addDay(1);

    livewire(Save::class)
        ->set('form.title', $chore['title'])
        ->set('form.description', $chore['description'])
        ->set('form.frequency_id', $chore['frequency_id']->value)
        ->set('form.chore_user_id', null)
        ->set('form.due_date', $due_date)
        ->call('save');

    assertDatabaseHas((new ChoreInstance)->getTable(), [
        'user_id' => $user->id,
        'due_date' => $due_date,
    ]);
});

test('chores can be created with advanced frequency', function () {
    $user = $this->testUser()['user'];
    $chore = Chore::factory()->raw();

    livewire(Save::class)
        ->set('form.title', $chore['title'])
        ->set('form.description', $chore['description'])
        ->set('form.frequency_id', FrequencyType::weekly->value)
        ->set('form.frequency_interval', 2)
        ->set('form.frequency_day_of', Carbon::WEDNESDAY)
        ->set('form.chore_user_id', $user->id)
        ->call('save');

    assertDatabaseHas((new Chore)->getTable(), [
        'user_id' => $user->id,
        'frequency_id' => FrequencyType::weekly->value,
        'frequency_interval' => 2,
        'frequency_day_of' => Carbon::WEDNESDAY,
    ]);
});

test('chores with day of week cannot be under 1', function () {
    $this->testUser();
    $component = livewire(Save::class)
        ->set('form.frequency_id', FrequencyType::weekly->value)
        ->call('showDayOfSection')
        ->set('form.frequency_day_of', 0)
        ->call('save');

    $component->assertHasErrors([
        'form.frequency_day_of' => 'Day of the Weeks must be between 1 and 7.',
    ]);
});

test('chores with day of week cannot be over 7', function () {
    $this->testUser();
    $component = livewire(Save::class)
        ->set('form.frequency_id', FrequencyType::weekly->value)
        ->call('showDayOfSection')
        ->set('form.frequency_day_of', 8)
        ->call('save');

    $component->assertHasErrors([
        'form.frequency_day_of' => 'Day of the Weeks must be between 1 and 7.',
    ]);
});

test('chores with day of month cannot be under 1', function () {
    $this->testUser();
    $component = livewire(Save::class)
        ->set('form.frequency_id', FrequencyType::monthly->value)
        ->call('showDayOfSection')
        ->set('form.frequency_day_of', -1)
        ->call('save');

    $component->assertHasErrors([
        'form.frequency_day_of' => 'Day of the Months must be between 1 and 31.',
    ]);
});

test('chores with day of month cannot be over 31', function () {
    $this->testUser();
    $component = livewire(Save::class)
        ->set('form.frequency_id', FrequencyType::monthly->value)
        ->call('showDayOfSection')
        ->set('form.frequency_day_of', 32)
        ->call('save');

    $component->assertHasErrors([
        'form.frequency_day_of' => 'Day of the Months must be between 1 and 31.',
    ]);
});

test('chores with day of quarter cannot be under 1', function () {
    $this->testUser();
    $component = livewire(Save::class)
        ->set('form.frequency_id', FrequencyType::quarterly->value)
        ->call('showDayOfSection')
        ->set('form.frequency_day_of', -1)
        ->call('save');

    $component->assertHasErrors([
        'form.frequency_day_of' => 'Day of the Quarters must be between 1 and 92.',
    ]);
});

test('chores with day of quarter cannot be over 92', function () {
    $this->testUser();
    $component = livewire(Save::class)
        ->set('form.frequency_id', FrequencyType::quarterly->value)
        ->call('showDayOfSection')
        ->set('form.frequency_day_of', 93)
        ->call('save');

    $component->assertHasErrors([
        'form.frequency_day_of' => 'Day of the Quarters must be between 1 and 92.',
    ]);
});

test('chores with day of year cannot be under 1', function () {
    $this->testUser();
    $component = livewire(Save::class)
        ->set('form.frequency_id', FrequencyType::yearly->value)
        ->call('showDayOfSection')
        ->set('form.frequency_day_of', -1)
        ->call('save');

    $component->assertHasErrors([
        'form.frequency_day_of' => 'Day of the Years must be between 1 and 365.',
    ]);
});

test('chores with day of year cannot be over 365', function () {
    $this->testUser();
    $component = livewire(Save::class)
        ->set('form.frequency_id', FrequencyType::yearly->value)
        ->call('showDayOfSection')
        ->set('form.frequency_day_of', 366)
        ->call('save');

    $component->assertHasErrors([
        'form.frequency_day_of' => 'Day of the Years must be between 1 and 365.',
    ]);
});

test('when you change to daily frequency day of is disabled', function () {
    $this->testUser();
    $chore = Chore::factory()->raw();
    $component = livewire(Save::class)
        ->set('form.title', $chore['title'])
        ->set('form.description', $chore['description'])
        ->set('form.frequency_id', FrequencyType::monthly->value)
        ->set('form.frequency_day_of', 5)
        ->set('show_on', true);

    $component->set('form.frequency_id', FrequencyType::daily->value);

    $component->assertSet('show_on', false);
    $component->assertSet('form.frequency_day_of', null);
});

test('when you change to does not repeat frequency day of is disabled', function () {
    $this->testUser();
    $chore = Chore::factory()->raw();
    $component = livewire(Save::class)
        ->set('form.title', $chore['title'])
        ->set('form.description', $chore['description'])
        ->set('form.frequency_id', FrequencyType::monthly->value)
        ->set('form.frequency_day_of', 5)
        ->set('show_on', true);

    $component->set('form.frequency_id', FrequencyType::doesNotRepeat->value);

    $component->assertSet('show_on', false);
    $component->assertSet('form.frequency_day_of', null);
});

test('when updating to another frequency id frequency day of changes to 1', function () {
    $this->testUser();
    $chore = Chore::factory()->raw();
    $component = livewire(Save::class)
        ->set('form.title', $chore['title'])
        ->set('form.description', $chore['description'])
        ->set('form.frequency_id', FrequencyType::yearly->value)
        ->set('form.frequency_day_of', 130)
        ->set('show_on', true);

    $component->set('form.frequency_id', FrequencyType::monthly->value);

    $component->assertSet('show_on', true);
    $component->assertSet('form.frequency_day_of', 1);
});

test('does not repeat does not show interval input', function () {
    $this->testUser();
    $component = livewire(Save::class)
        ->set('form.frequency_id', FrequencyType::doesNotRepeat->value);

    $component->assertDontSee('Every');
});
