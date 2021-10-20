<?php

namespace Tests\Unit;

use App\Enums\Frequency;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChoreInstanceCompleteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * More testing of this happens in FrequencyTest directly.
     * Removed the more intensive tests from here in favor of testing there.
     */

    /** @test */
    public function do_not_repeat_chore_creates_no_instance()
    {
        // Arrange
        // Create Chore with Daily Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => Frequency::DOES_NOT_REPEAT,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 day.
        $this->assertEquals(
            null,
            $chore->nextChoreInstance
        );
    }

    /** @test */
    public function chores_can_be_completed_with_a_frequency()
    {
        // Arrange
        // Create Chore with Daily Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => Frequency::DAILY,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 day.
        $this->assertEquals(
            today()->addDay()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function chores_can_be_completed_with_a_frequency_plus_interval()
    {
        // Arrange
        // Create Chores with Daily Frequency every 2 and every 3 days
        $chore1 = Chore::factory()->create([
            'frequency_id'       => Frequency::DAILY,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => Frequency::WEEKLY,
            'frequency_interval' => 3,
        ]);

        // Act
        // Create Chore instance
        $chore1->createNewInstance();
        $chore2->createNewInstance();

        // Assert
        // Chore instance due dates are in 2 and 3 days respectively.
        $this->assertEquals(
            today()->addDays(2)->toDateString(),
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            today()->addWeeks(3)->toDateString(),
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function chores_can_be_completed_with_day_of_frequency()
    {
        // Arrange
        // Create Chores with two different day of frequencies
        Carbon::setTestNow('2021-07-06');
        $date   = Carbon::parse('2021-07-06');
        $chore1 = Chore::factory()->create([
            'frequency_id'       => Frequency::WEEKLY,
            'frequency_interval' => 1,
            'frequency_day_of'   => Carbon::TUESDAY,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => Frequency::MONTHLY,
            'frequency_interval' => 1,
            'frequency_day_of'   => 17,
        ]);

        // Act
        // Create Chore instance
        $chore1->createNewInstance();
        $chore2->createNewInstance();

        // Assert
        // Chore instance due dates are in 2 and 3 days respectively.
        $this->assertEquals(
            '2021-07-13',
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            '2021-08-17',
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function completing_a_chore_instance_creates_a_new_instance_with_same_owner()
    {
        // Arrange
        // create chore with user and instance
        $user  = User::factory()->create();
        $chore = Chore::factory()->for($user)->withFirstInstance()->create();

        // Act
        // Complete chore instance
        $chore->complete();

        // Assert
        // Next chore instance has the same user
        $this->assertEquals(
            $user->id,
            $chore->nextChoreInstance->user_id,
        );
    }

    /** @test */
    public function when_a_chore_is_completed_the_completed_by_id_is_set_to_the_user_completing_it()
    {
        // Arrange
        // Create a test user acting as and a chore assigned to a different user.
        $acting_as_user = $this->testUser()['user'];
        $assigned_user  = User::factory()->create();
        $chore          = Chore::factory()->for($assigned_user)->withFirstInstance()->create();

        // Act
        // Complete Chore
        $chore->complete();

        // Assert
        // Completed chore instance is marked completed by acting_as_user
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'user_id'         => $assigned_user->id,
            'completed_by_id' => $acting_as_user->id,
            'chore_id'        => $chore->id,
        ]);
    }

    /** @test */
    public function when_a_chore_assigned_to_a_team_is_completed_the_next_instance_is_assigned_to_the_next_person_alphabetically()
    {
        // Arrange
        // Create three users, a chore with a first instance assigned to the second user
        $user_and_team = $this->testUser(['name' => 'Albert Albany']);
        $user1         = $user_and_team['user'];
        $team          = $user_and_team['team'];
        $users         = User::factory()
            ->hasAttached($team)
            ->count(2)
            ->sequence(
                ['name' => 'Bobby Boston'],
                ['name' => 'Charlie Chicago'],
            )
            ->create();
        $user2 = $users->first();
        $user3 = $users->last();
        $chore = Chore::factory(['frequency_id' => Frequency::DAILY])
            ->for($team)
            ->assignedToTeam()
            ->has(ChoreInstance::factory()->for($user2))
            ->create();

        // Act
        // Complete the first instnace
        $chore->complete();

        // Assert
        // The next chore instance is assigned to the third user
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'user_id'        => $user3->id,
            'chore_id'       => $chore->id,
            'completed_date' => null,
        ]);
    }

    /** @test */
    public function when_an_instance_is_assigned_to_the_last_person_alphabetically_it_will_wrap_around()
    {
        // Arrange
        // Create three users, a chore with a first instance assigned to the third user
        $user_and_team = $this->testUser(['name' => 'Albert Albany']);
        $user1         = $user_and_team['user'];
        $team          = $user_and_team['team'];
        $users         = User::factory()
            ->hasAttached($team)
            ->count(2)
            ->sequence(
                ['name' => 'Bobby Boston'],
                ['name' => 'Charlie Chicago'],
            )
            ->create();
        $user2 = $users->first();
        $user3 = $users->last();
        $chore = Chore::factory(['frequency_id' => Frequency::DAILY])
            ->for($team)
            ->assignedToTeam()
            ->has(ChoreInstance::factory()->for($user3))
            ->create();

        // Act
        // Complete the first instnace
        $chore->complete();

        // Assert
        // The next chore instance is assigned to the first user
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'user_id'        => $user1->id,
            'chore_id'       => $chore->id,
            'completed_date' => null,
        ]);
    }

    /** @test */
    public function when_chore_is_completed_in_the_past_the_next_instance_date_is_based_on_that_date()
    {
        // Arrange
        // Create chore with predictable frequency
        $date  = today();
        $chore = Chore::factory()->withFirstInstance()->create([
            'frequency_id'       => Frequency::DAILY,
            'frequency_interval' => 4,
        ]);

        // Act
        // Complete chore
        $chore->complete(null, $date->subDays(3));

        // Assert
        // new instance counts from the completed dated
        $this->assertEquals(
            today()->addDay()->toDateString(),
            $chore->refresh()->nextChoreInstance->due_date->toDateString(),
        );
    }
}
