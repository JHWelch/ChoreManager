<?php

namespace Tests\Feature\ChoreInstances;

use App\Http\Livewire\ChoreInstances\Index as ChoreInstancesIndex;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function chore_instance_index_page_can_be_reached()
    {
        // Arrange
        // Create a test user
        $this->testUser()['user'];

        // Act
        // Navigate to Chore instance Index page
        $response = $this->get(route('chore_instances.index'));

        // Assert
        // A page is successfully returned
        $response->assertOk();
    }

    /** @test */
    public function chores_with_chore_instances_show_on_index()
    {
        // Arrange
        // Create a chore with a chore instance
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->withFirstInstance(today())->create();

        // Act
        // Open chore instance index
        $component = Livewire::test(ChoreInstancesIndex::class);

        // Assert
        // Chore and instance date is show on page
        $component->assertSee($chore->title);
    }

    /** @test */
    public function chores_without_chore_instances_do_not_show_on_index()
    {
        // Arrange
        // Createa a chore without chore instance
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->create();

        // Act
        // Open chore instance index
        $component = Livewire::test(ChoreInstancesIndex::class);

        // Assert
        // I do not see that chore's title
        $component->assertDontSee($chore->title);
    }

    /** @test */
    public function when_there_are_no_chore_instances_see_empty_state()
    {
        // Arrange
        // create user
        $this->testUser();

        // Act
        // Go to Index page
        $component = Livewire::test(ChoreInstancesIndex::class);

        // Assert
        // See empty state
        $component->assertSee('All done for today');
    }

    /** @test */
    public function future_chores_do_not_show_by_default()
    {
        // Arrange
        // Create two chores, one due today, one in future
        $user   = $this->testUser()['user'];
        $chore1 = Chore::factory()
            ->for($user)
            ->withFirstInstance(today())
            ->create();
        $chore2 = Chore::factory()
            ->for($user)
            ->withFirstInstance(today()
            ->addDays(4))->create();

        // Act
        // View Index page
        $component = Livewire::test(ChoreInstancesIndex::class);

        // Assert
        // Can see the chore due today, but not the one in the future.
        $component->assertSee($chore1->title);
        $component->assertDontSee($chore2->title);
    }

    /** @test */
    public function user_can_show_future_chores()
    {
        // Arrange
        // Create chore in the future
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()
            ->for($user)
            ->withFirstInstance(today()
            ->addDays(4))->create();

        // Act
        // View index page and toggle showing future chores
        $component = Livewire::test(ChoreInstancesIndex::class)
            ->call('toggleShowFutureChores');

        // Assert
        // User can see future chore
        $component->assertSee($chore->title);
    }

    /** @test */
    public function show_future_chores_is_remembered_when_revisiting_page()
    {
        // Arrange
        // Create chore in the future
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()
            ->for($user)
            ->withFirstInstance(today()
            ->addDays(4))->create();

        // Act
        // Open component and toggle Show, load another component
        Livewire::test(ChoreInstancesIndex::class)
            ->call('toggleShowFutureChores');
        $component = Livewire::test(ChoreInstancesIndex::class);

        // Assert
        // User can see future chore
        $component->assertSee($chore->title);
    }

    /** @test */
    public function it_can_snooze_chores_due_today_for_a_user_until_tomorrow()
    {
        // Arrange
        // Create chores due today, and one other chore
        $this->testUser();
        $chores = Chore::factory()
            ->count(3)
            ->withFirstInstance(today())
            ->for($this->user)
            ->create();
        $other_chore = Chore::factory()
            ->withFirstInstance(today()->subDays(3))
            ->for($this->user)
            ->create();
        $tomorrow = today()->addDay();

        // Act
        // Snooze Chores due today until tomorrow
        Livewire::test(ChoreInstancesIndex::class)
            ->call('snoozeGroupUntilTomorrow', 'today');

        // Assert
        // Chores due today are snoozed, the other is not
        foreach ($chores as $chore) {
            $this->assertDatabaseHas(ChoreInstance::class, [
                'chore_id' => $chore->id,
                'due_date' => $tomorrow,
            ]);
        }

        $this->assertDatabaseMissing(ChoreInstance::class, [
            'chore_id' => $other_chore->id,
            'due_date' => $tomorrow,
        ]);
    }

    /** @test */
    public function it_can_snooze_chores_due_today_for_a_user_untiL_the_weekend()
    {
        // Arrange
        // Create chores due today, and one other chore
        $this->testUser();
        $chores = Chore::factory()
               ->count(3)
               ->withFirstInstance(today())
               ->for($this->user)
               ->create();
        $other_chore = Chore::factory()
               ->withFirstInstance(today()->subDays(3))
               ->for($this->user)
               ->create();
        $tomorrow = today()->addDay();

        // Act
        // Snooze Chores due today until tomorrow
        Livewire::test(ChoreInstancesIndex::class)
               ->call('snoozeGroupUntilTomorrow', 'today');

        // Assert
        // Chores due today are snoozed, the other is not
        foreach ($chores as $chore) {
            $this->assertDatabaseHas(ChoreInstance::class, [
                   'chore_id' => $chore->id,
                   'due_date' => $tomorrow,
               ]);
        }

        $this->assertDatabaseMissing(ChoreInstance::class, [
               'chore_id' => $other_chore->id,
               'due_date' => $tomorrow,
           ]);
    }

    /** @test */
    public function it_can_snooze_chores_due_in_the_past_for_a_user_until_tomorrow()
    {
        // Arrange
        // Create chores due in the past, and one other chore
        $this->testUser();
        $chores = Chore::factory()
            ->count(3)
            ->withFirstInstance(today()->subDays(2))
            ->for($this->user)
            ->create();
        $other_chore = Chore::factory()
            ->withFirstInstance(today())
            ->for($this->user)
            ->create();
        $tomorrow = today()->addDay();

        // Act
        // Snooze Chores due in the past until tomorrow
        Livewire::test(ChoreInstancesIndex::class)
            ->call('snoozeGroupUntilTomorrow', 'past_due');

        // Assert
        // Chores due today are snoozed, the other is not
        foreach ($chores as $chore) {
            $this->assertDatabaseHas(ChoreInstance::class, [
                'chore_id' => $chore->id,
                'due_date' => $tomorrow,
            ]);
        }

        $this->assertDatabaseMissing(ChoreInstance::class, [
            'chore_id' => $other_chore->id,
            'due_date' => $tomorrow,
        ]);
    }

    /** @test */
    public function it_can_snooze_chores_due_in_the_past_for_a_user_untiL_the_weekend()
    {
        // Arrange
        // Create chores due in the past, and one other chore
        $this->travelToKnownMonday();
        $this->testUser();
        $chores = Chore::factory()
            ->count(3)
            ->withFirstInstance(today()->subDay())
            ->for($this->user)
            ->create();
        $other_chore = Chore::factory()
            ->withFirstInstance(today())
            ->for($this->user)
            ->create();

        // Act
        // Snooze Chores due in the past until tomorrow
        Livewire::test(ChoreInstancesIndex::class)
            ->call('snoozeGroupUntilWeekend', 'past_due');

        // Assert
        // Chores due today are snoozed, the other is not
        foreach ($chores as $chore) {
            $this->assertDatabaseHas(ChoreInstance::class, [
                'chore_id' => $chore->id,
                'due_date' => $this->knownSaturday(),
            ]);
        }

        $this->assertDatabaseMissing(ChoreInstance::class, [
            'chore_id' => $other_chore->id,
            'due_date' => $this->knownSaturday(),
        ]);
    }

    /** @test */
    public function it_wont_snooze_chores_due_today_for_a_team_untiL_the_weekend_if_filter_is_user()
    {
        // Arrange
        // Create chores due in the past, and one other chore
        $this->travelToKnownMonday();
        $this->testUser();

        $chore = Chore::factory()
             ->withFirstInstance(today())
             ->for(User::factory()->hasAttached($this->team))
             ->create();

        // Act
        // Snooze Chores due in the past until tomorrow
        Livewire::test(ChoreInstancesIndex::class)
             ->call('snoozeGroupUntilWeekend', 'today');

        // Assert
        // Chores due today are snoozed, the other is not
        $this->assertDatabaseMissing(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $this->knownSaturday(),
        ]);
    }

    /** @test */
    public function it_wont_snooze_chores_due_in_the_past_for_a_team_untiL_the_weekend_if_filter_is_user()
    {
        // Arrange
        // Create chores due in the past, and one other chore
        $this->travelToKnownMonday();
        $this->testUser();

        $chore = Chore::factory()
             ->withFirstInstance(today()->subDay())
             ->for(User::factory()->hasAttached($this->team))
             ->create();

        // Act
        // Snooze Chores due in the past until tomorrow
        Livewire::test(ChoreInstancesIndex::class)
             ->call('snoozeGroupUntilWeekend', 'past_due');

        // Assert
        // Chores due today are snoozed, the other is not
        $this->assertDatabaseMissing(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $this->knownSaturday(),
        ]);
    }

    /** @test */
    public function snoozes_chores_owned_by_team_but_assigned_to_user()
    {
        // Arrange
        // Create chores due in the past, and one other chore
        $this->testUser();
        $chore = Chore::factory()
            ->withFirstInstance(today()->subDays(2), $this->user->id)
            ->for($this->team)
            ->create();
        $tomorrow = today()->addDay();

        // Act
        // Snooze Chores due in the past until tomorrow
        Livewire::test(ChoreInstancesIndex::class)
            ->call('snoozeGroupUntilTomorrow', 'past_due');

        // Assert
        // Chores due today are snoozed, the other is not
        $this->assertDatabaseHas(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $tomorrow,
        ]);
    }
}
