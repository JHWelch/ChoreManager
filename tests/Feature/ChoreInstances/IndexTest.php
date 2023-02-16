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
    public function chore_instance_index_page_can_be_reached(): void
    {
        $this->testUser()['user'];

        $response = $this->get(route('chore_instances.index'));

        $response->assertOk();
    }

    /** @test */
    public function chores_with_chore_instances_show_on_index(): void
    {
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->withFirstInstance(today())->create();

        $component = Livewire::test(ChoreInstancesIndex::class);

        $component->assertSee($chore->title);
    }

    /** @test */
    public function chores_without_chore_instances_do_not_show_on_index(): void
    {
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->create();

        $component = Livewire::test(ChoreInstancesIndex::class);

        $component->assertDontSee($chore->title);
    }

    /** @test */
    public function when_there_are_no_chore_instances_see_empty_state(): void
    {
        $this->testUser();

        $component = Livewire::test(ChoreInstancesIndex::class);

        $component->assertSee('All done for today');
    }

    /** @test */
    public function future_chores_do_not_show_by_default(): void
    {
        $user   = $this->testUser()['user'];
        $chore1 = Chore::factory()
            ->for($user)
            ->withFirstInstance(today())
            ->create();
        $chore2 = Chore::factory()
            ->for($user)
            ->withFirstInstance(today()->addDays(4))
            ->create();

        $component = Livewire::test(ChoreInstancesIndex::class);

        $component->assertSee($chore1->title);
        $component->assertDontSee($chore2->title);
    }

    /** @test */
    public function user_can_show_future_chores(): void
    {
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()
            ->for($user)
            ->withFirstInstance(today()
            ->addDays(4))->create();

        $component = Livewire::test(ChoreInstancesIndex::class)
            ->call('toggleShowFutureChores');

        $component->assertSeeInOrder(['Future', $chore->title]);
    }

    /** @test */
    public function show_future_chores_is_remembered_when_revisiting_page(): void
    {
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()
            ->for($user)
            ->withFirstInstance(today()
            ->addDays(4))->create();

        Livewire::test(ChoreInstancesIndex::class)
            ->call('toggleShowFutureChores');
        $component = Livewire::test(ChoreInstancesIndex::class);

        $component->assertSee($chore->title);
    }

    /** @test */
    public function it_can_snooze_chores_due_today_for_a_user_until_tomorrow(): void
    {
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

        Livewire::test(ChoreInstancesIndex::class)
            ->call('snoozeGroupUntilTomorrow', 'today');

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
    public function it_can_snooze_chores_due_today_for_a_user_untiL_the_weekend(): void
    {
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

        Livewire::test(ChoreInstancesIndex::class)
               ->call('snoozeGroupUntilTomorrow', 'today');

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
    public function it_can_snooze_chores_due_in_the_past_for_a_user_until_tomorrow(): void
    {
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

        Livewire::test(ChoreInstancesIndex::class)
            ->call('snoozeGroupUntilTomorrow', 'past_due');

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
    public function it_can_snooze_chores_due_in_the_past_for_a_user_untiL_the_weekend(): void
    {
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

        Livewire::test(ChoreInstancesIndex::class)
            ->call('snoozeGroupUntilWeekend', 'past_due');

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
    public function it_wont_snooze_chores_due_today_for_a_team_untiL_the_weekend_if_filter_is_user(): void
    {
        $this->travelToKnownMonday();
        $this->testUser();

        $chore = Chore::factory()
             ->withFirstInstance(today())
             ->for(User::factory()->hasAttached($this->team))
             ->create();

        Livewire::test(ChoreInstancesIndex::class)
             ->call('snoozeGroupUntilWeekend', 'today');

        $this->assertDatabaseMissing(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $this->knownSaturday(),
        ]);
    }

    /** @test */
    public function it_wont_snooze_chores_due_in_the_past_for_a_team_untiL_the_weekend_if_filter_is_user(): void
    {
        $this->travelToKnownMonday();
        $this->testUser();

        $chore = Chore::factory()
             ->withFirstInstance(today()->subDay())
             ->for(User::factory()->hasAttached($this->team))
             ->create();

        Livewire::test(ChoreInstancesIndex::class)
             ->call('snoozeGroupUntilWeekend', 'past_due');

        $this->assertDatabaseMissing(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $this->knownSaturday(),
        ]);
    }

    /** @test */
    public function snoozes_chores_owned_by_team_but_assigned_to_user(): void
    {
        $this->testUser();
        $chore = Chore::factory()
            ->withFirstInstance(today()->subDays(2), $this->user->id)
            ->for($this->team)
            ->create();
        $tomorrow = today()->addDay();

        Livewire::test(ChoreInstancesIndex::class)
            ->call('snoozeGroupUntilTomorrow', 'past_due');

        $this->assertDatabaseHas(ChoreInstance::class, [
            'chore_id' => $chore->id,
            'due_date' => $tomorrow,
        ]);
    }

    /** @test */
    public function chore_instances_are_split_into_groups_based_on_date(): void
    {
        $this->testUser();
        Chore::factory(['title' => 'walk dog'])
            ->for($this->user)
            ->withFirstInstance(today()->addDay(), $this->user)
            ->create();
        Chore::factory(['title' => 'do laundry'])
            ->for($this->user)
            ->withFirstInstance(today()->subDay(), $this->user)
            ->create();
        Chore::factory(['title' => 'clean dishes'])
            ->for($this->user)
            ->withFirstInstance(today(), $this->user)
            ->create();

        Livewire::test(ChoreInstancesIndex::class)
            ->assertSeeInOrder([
                'Past due',
                'do laundry',
                'Today',
                'clean dishes',
            ]);
    }
}
