<?php

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\Team;
use App\Models\User;
use App\Rules\FrequencyDayOf;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

test('chore edit page can be reached', function () {
    // Arrange
    // Create a test user
    $this->testUser();

    // Act
    // Navigate to Chore Create page
    $response = $this->get(route('chores.create'));

    // Assert
    // A page is successfully returned
    $response->assertStatus(200);
});

test('can create chore', function () {
    // Arrange
    $user = $this->testUser()['user'];

    // Act
    Livewire::test(Save::class)
        ->set('chore.title', 'Do dishes')
        ->set('chore.description', 'Do the dishes every night.')
        ->set('chore.frequency_id', 1)
        ->call('save');

    // Assert
    $this->assertDatabaseHas((new Chore)->getTable(), [
        'title'        => 'Do dishes',
        'description'  => 'Do the dishes every night.',
        'frequency_id' => Frequency::DAILY,
        'user_id'      => $user->id,
    ]);
});

test('a user can assign a chore to another team member', function () {
    // Arrange
    // Create team with two users, log in with first
    $users         = User::factory()->count(2)->hasTeams()->create();
    $team          = Team::first();
    $assigned_user = $users->pop();
    $chore         = Chore::factory()->raw();

    $this->actingAs($users->first());
    $users->first()->switchTeam($team);

    // Act
    // Create chore, assign to user
    Livewire::test(Save::class)
         ->set('chore.title', $chore['title'])
         ->set('chore.description', $chore['description'])
         ->set('chore.frequency_id', $chore['frequency_id'])
         ->set('chore.user_id', $assigned_user->id)
         ->set('chore_instance.due_date', null)
         ->call('save');

    // Assert
    // The chore is created and assigned to that user
    $this->assertDatabaseHas((new Chore)->getTable(), [
         'user_id'      => $assigned_user->id,
         'title'        => $chore['title'],
         'description'  => $chore['description'],
         'frequency_id' => $chore['frequency_id'],
     ]);
});

test('a chore can be assigned to a team', function () {
    // Arrange
    // Create user and chore info
    $this->testUser();
    $chore = Chore::factory()->raw();

    // Act
    // Navigate to create chore, create chore without owner
    $component = Livewire::test(Save::class)
        ->set('chore.title', $chore['title'])
        ->set('chore.description', $chore['description'])
        ->set('chore.frequency_id', $chore['frequency_id'])
        ->set('chore.user_id', null)
        ->call('save');

    // Assert
    // Chore is create with no owner.
    $component->assertHasNoErrors();
    $this->assertDatabaseHas((new Chore)->getTable(), [
        'title'        => $chore['title'],
        'description'  => $chore['description'],
        'frequency_id' => $chore['frequency_id'],
        'user_id'      => null,
    ]);
});

test('chores assigned to team with due date create instance assigned to team member', function () {
    // Arrange
    // Create user and chore info
    $user     = $this->testUser()['user'];
    $chore    = Chore::factory()->raw();
    $due_date = today()->addDay(1);

    // Act
    // Navigate to create chore, create chore without owner
    Livewire::test(Save::class)
        ->set('chore.title', $chore['title'])
        ->set('chore.description', $chore['description'])
        ->set('chore.frequency_id', $chore['frequency_id'])
        ->set('chore.user_id', null)
        ->set('chore_instance.due_date', $due_date)
        ->call('save');

    // Assert
    // Chore is create with no owner.
    $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
        'user_id'  => $user->id,
        'due_date' => $due_date,
    ]);
});

test('chores can be created with advanced frequency', function () {
    // Arrange
    // Create user and chore info
    $user  = $this->testUser()['user'];
    $chore = Chore::factory()->raw();

    // Act
    // Navigate to create chore, create chore with advanced frequency
    Livewire::test(Save::class)
        ->set('chore.title', $chore['title'])
        ->set('chore.description', $chore['description'])
        ->set('chore.frequency_id', Frequency::WEEKLY)
        ->set('chore.frequency_interval', 2)
        ->set('chore.frequency_day_of', Carbon::WEDNESDAY)
        ->set('chore.user_id', $user->id)
        ->call('save');

    // Assert
    // Chore is created in database
    $this->assertDatabaseHas((new Chore)->getTable(), [
        'user_id'            => $user->id,
        'frequency_id'       => Frequency::WEEKLY,
        'frequency_interval' => 2,
        'frequency_day_of'   => Carbon::WEDNESDAY,
    ]);
});

test('chores with day of week cannot be under 1', function () {
    // Act
    // Set frequency and frequency day of.
    $component = getFrequencyValidationComponent()
        ->set('chore.frequency_id', Frequency::WEEKLY)
        ->call('showDayOfSection')
        ->set('chore.frequency_day_of', 0)
        ->call('save');

    // Assert
    // Has error
    $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
});

test('chores with day of week cannot be over 7', function () {
    // Act
    // Set frequency and frequency day of.
    $component = getFrequencyValidationComponent()
        ->set('chore.frequency_id', Frequency::WEEKLY)
        ->call('showDayOfSection')
        ->set('chore.frequency_day_of', 8)
        ->call('save');

    // Assert
    // Has error
    $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
});

test('chores with day of month cannot be under 1', function () {
    // Act
    // Set frequency and frequency day of.
    $component = getFrequencyValidationComponent()
        ->set('chore.frequency_id', Frequency::MONTHLY)
        ->call('showDayOfSection')
        ->set('chore.frequency_day_of', -1)
        ->call('save');

    // Assert
    // Has error
    $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
});

test('chores with day of month cannot be over 31', function () {
    // Act
    // Set frequency and frequency day of.
    $component = getFrequencyValidationComponent()
        ->set('chore.frequency_id', Frequency::MONTHLY)
        ->call('showDayOfSection')
        ->set('chore.frequency_day_of', 32)
        ->call('save');

    // Assert
    // Has error
    $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
});

test('chores with day of quarter cannot be under 1', function () {
    // Act
    // Set frequency and frequency day of.
    $component = getFrequencyValidationComponent()
        ->set('chore.frequency_id', Frequency::QUARTERLY)
        ->call('showDayOfSection')
        ->set('chore.frequency_day_of', -1)
        ->call('save');

    // Assert
    // Has error
    $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
});

test('chores with day of quarter cannot be over 92', function () {
    // Act
    // Set frequency and frequency day of.
    $component = getFrequencyValidationComponent()
        ->set('chore.frequency_id', Frequency::QUARTERLY)
        ->call('showDayOfSection')
        ->set('chore.frequency_day_of', 93)
        ->call('save');

    // Assert
    // Has error
    $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
});

test('chores with day of year cannot be under 1', function () {
    // Act
    // Set frequency and frequency day of.
    $component = getFrequencyValidationComponent()
        ->set('chore.frequency_id', Frequency::YEARLY)
        ->call('showDayOfSection')
        ->set('chore.frequency_day_of', -1)
        ->call('save');

    // Assert
    // Has error
    $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
});

test('chores with day of year cannot be over 365', function () {
    // Act
    // Set frequency and frequency day of.
    $component = getFrequencyValidationComponent()
        ->set('chore.frequency_id', Frequency::YEARLY)
        ->call('showDayOfSection')
        ->set('chore.frequency_day_of', 366)
        ->call('save');

    // Assert
    // Has error
    $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
});

test('when you change to daily frequency day of is disabled', function () {
    // Arrange
    // Open chore save page, with frequency and show on
    $this->testUser();
    $chore     = Chore::factory()->raw();
    $component = Livewire::test(Save::class)
        ->set('chore.title', $chore['title'])
        ->set('chore.description', $chore['description'])
        ->set('chore.frequency_id', Frequency::MONTHLY)
        ->set('chore.frequency_day_of', 5)
        ->set('show_on', true);

    // Act
    // Update frequency_id
    $component->set('chore.frequency_id', Frequency::DAILY);

    // Assert
    // Show on should be off, and frequency_day_of is cleared
    $component->assertSet('show_on', false);
    $component->assertSet('chore.frequency_day_of', null);
});

test('when you change to does not repeat frequency day of is disabled', function () {
    // Arrange
    // Open chore save page, with frequency and show on
    $this->testUser();
    $chore     = Chore::factory()->raw();
    $component = Livewire::test(Save::class)
        ->set('chore.title', $chore['title'])
        ->set('chore.description', $chore['description'])
        ->set('chore.frequency_id', Frequency::MONTHLY)
        ->set('chore.frequency_day_of', 5)
        ->set('show_on', true);

    // Act
    // Update frequency_id
    $component->set('chore.frequency_id', Frequency::DOES_NOT_REPEAT);

    // Assert
    // Show on should be off, and frequency_day_of is cleared
    $component->assertSet('show_on', false);
    $component->assertSet('chore.frequency_day_of', null);
});

test('when updating to another frequency id frequency day of changes to 1', function () {
    // Arrange
    // Open chore save page, with yearly frequency and show on
    $this->testUser();
    $chore     = Chore::factory()->raw();
    $component = Livewire::test(Save::class)
        ->set('chore.title', $chore['title'])
        ->set('chore.description', $chore['description'])
        ->set('chore.frequency_id', Frequency::YEARLY)
        ->set('chore.frequency_day_of', 130)
        ->set('show_on', true);

    // Act
    // Update frequency_id to monthly
    $component->set('chore.frequency_id', Frequency::MONTHLY);

    // Assert
    // Show on should still be on, and frequency_day_of is one
    $component->assertSet('show_on', true);
    $component->assertSet('chore.frequency_day_of', 1);
});

// Helpers
/**
     * Return a livewire testable already filled with most fields for validating frequency.
     *
     * @return \Livewire\Testing\TestableLivewire
     */
function getFrequencyValidationComponent()
{
    test()->testUser()['user'];

    return Livewire::test(Save::class);
}
