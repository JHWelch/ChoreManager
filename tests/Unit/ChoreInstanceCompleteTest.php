<?php

namespace Tests\Unit;

use App\Enums\Frequency;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChoreInstanceCompleteTest extends TestCase
{
    use RefreshDatabase;

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
    public function daily()
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
    public function weekly()
    {
        // Arrange
        // Create Chore with Daily Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => Frequency::WEEKLY,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 day.
        $this->assertEquals(
            today()->addWeek()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function monthly()
    {
        // Arrange
        // Create Chore with monthly Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => Frequency::MONTHLY,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 month.
        $this->assertEquals(
            today()->addMonthNoOverflow()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function quarterly()
    {
        // Arrange
        // Create Chore with monthly Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => Frequency::QUARTERLY,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 month.
        $this->assertEquals(
            today()->addQuarterNoOverflow()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function yearly()
    {
        // Arrange
        // Create Chore with monthly Frequency
        $chore = Chore::factory()->create([
            'frequency_id' => Frequency::YEARLY,
        ]);

        // Act
        // Create Chore instance
        $chore->createNewInstance();

        // Assert
        // Chore instance due date is in 1 month.
        $this->assertEquals(
            today()->addYearNoOverflow()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function daily_plus_interval()
    {
        // Arrange
        // Create Chores with Daily Frequency every 2 and every 3 days
        $chore1 = Chore::factory()->create([
            'frequency_id'       => Frequency::DAILY,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => Frequency::DAILY,
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
            today()->addDays(3)->toDateString(),
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function weekly_plus_interval()
    {
        // Arrange
        // Create Chores with weekly Frequency every 2 and every 3 weeks
        $chore1 = Chore::factory()->create([
            'frequency_id'       => Frequency::WEEKLY,
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
            today()->addWeeks(2)->toDateString(),
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            today()->addWeeks(3)->toDateString(),
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function monthly_plus_interval()
    {
        // Arrange
        // Create Chores with monthly Frequency every 2 and every 3 months
        $chore1 = Chore::factory()->create([
            'frequency_id'       => Frequency::MONTHLY,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => Frequency::MONTHLY,
            'frequency_interval' => 3,
        ]);

        // Act
        // Create Chore instance
        $chore1->createNewInstance();
        $chore2->createNewInstance();

        // Assert
        // Chore instance due dates are in 2 and 3 days respectively.
        $this->assertEquals(
            today()->addMonthsNoOverflow(2)->toDateString(),
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            today()->addMonthsNoOverflow(3)->toDateString(),
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function quarterly_plus_interval()
    {
        // Arrange
        // Create Chores with quarterly Frequency every 2 and every 3 months
        $chore1 = Chore::factory()->create([
            'frequency_id'       => Frequency::QUARTERLY,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => Frequency::QUARTERLY,
            'frequency_interval' => 3,
        ]);

        // Act
        // Create Chore instance
        $chore1->createNewInstance();
        $chore2->createNewInstance();

        // Assert
        // Chore instance due dates are in 2 and 3 days respectively.
        $this->assertEquals(
            today()->addQuartersNoOverflow(2)->toDateString(),
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            today()->addQuartersNoOverflow(3)->toDateString(),
            $chore2->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function yearly_plus_interval()
    {
        // Arrange
        // Create Chores with yearly Frequency every 2 and every 3 months
        $chore1 = Chore::factory()->create([
            'frequency_id'       => Frequency::YEARLY,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id'       => Frequency::YEARLY,
            'frequency_interval' => 3,
        ]);

        // Act
        // Create Chore instance
        $chore1->createNewInstance();
        $chore2->createNewInstance();

        // Assert
        // Chore instance due dates are in 2 and 3 days respectively.
        $this->assertEquals(
            today()->addYearsNoOverflow(2)->toDateString(),
            $chore1->nextChoreInstance->due_date->toDateString(),
        );
        $this->assertEquals(
            today()->addYearsNoOverflow(3)->toDateString(),
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
        $chore->nextChoreInstance->complete();

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
        $chore->nextChoreInstance->complete();

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
        $chore->nextChoreInstance->complete();

        // Assert
        // The next chore instance is assigned to the third user
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'user_id'        => $user3->id,
            'chore_id'       => $chore->id,
            'completed_date' => null,
        ]);
    }
}
