<?php

namespace Tests\Unit;

use App\Enums\FrequencyType;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class ChoreInstanceCompleteTest extends TestCase
{
    /**
     * More testing of this happens in FrequencyTest directly.
     * Removed the more intensive tests from here in favor of testing there.
     */

    /** @test */
    public function do_not_repeat_chore_creates_no_instance(): void
    {
        $chore = Chore::factory()->create([
            'frequency_id' => FrequencyType::doesNotRepeat,
        ]);

        $chore->createNewInstance();

        $this->assertEquals(
            null,
            $chore->nextChoreInstance
        );
    }

    /** @test */
    public function chores_can_be_completed_with_a_frequency(): void
    {
        $chore = Chore::factory()->create([
            'frequency_id' => FrequencyType::daily,
        ]);

        $chore->createNewInstance();

        $this->assertEquals(
            today()->addDay()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function chores_can_be_completed_with_a_frequency_plus_interval(): void
    {
        $chore1 = Chore::factory()->create([
            'frequency_id' => FrequencyType::daily,
            'frequency_interval' => 2,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id' => FrequencyType::weekly,
            'frequency_interval' => 3,
        ]);

        $chore1->createNewInstance();
        $chore2->createNewInstance();

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
    public function chores_can_be_completed_with_day_of_frequency(): void
    {
        Carbon::setTestNow('2021-07-06');
        $chore1 = Chore::factory()->create([
            'frequency_id' => FrequencyType::weekly,
            'frequency_interval' => 1,
            'frequency_day_of' => Carbon::TUESDAY,
        ]);
        $chore2 = Chore::factory()->create([
            'frequency_id' => FrequencyType::monthly,
            'frequency_interval' => 1,
            'frequency_day_of' => 17,
        ]);

        $chore1->createNewInstance();
        $chore2->createNewInstance();

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
    public function completing_a_chore_instance_creates_a_new_instance_with_same_owner(): void
    {
        $user = User::factory()->create();
        $chore = Chore::factory()->for($user)->withFirstInstance()->create();

        $chore->complete();

        $this->assertEquals(
            $user->id,
            $chore->nextChoreInstance->user_id,
        );
    }

    /** @test */
    public function when_a_chore_is_completed_the_completed_by_id_is_set_to_the_user_completing_it(): void
    {
        $acting_as_user = $this->testUser()['user'];
        $assigned_user = User::factory()->create();
        $chore = Chore::factory()->for($assigned_user)->withFirstInstance()->create();

        $chore->complete();

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'user_id' => $assigned_user->id,
            'completed_by_id' => $acting_as_user->id,
            'chore_id' => $chore->id,
        ]);
    }

    /** @test */
    public function when_a_chore_assigned_to_a_team_is_completed_the_next_instance_is_assigned_to_the_next_person_alphabetically(): void
    {
        $user_and_team = $this->testUser(['name' => 'Albert Albany']);
        $team = $user_and_team['team'];
        $users = User::factory()
            ->hasAttached($team)
            ->count(2)
            ->sequence(
                ['name' => 'Bobby Boston'],
                ['name' => 'Charlie Chicago'],
            )
            ->create();
        $user2 = $users->first();
        $user3 = $users->last();
        $chore = Chore::factory(['frequency_id' => FrequencyType::daily])
            ->for($team)
            ->assignedToTeam()
            ->has(ChoreInstance::factory()->for($user2))
            ->create();

        $chore->complete();

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'user_id' => $user3->id,
            'chore_id' => $chore->id,
            'completed_date' => null,
        ]);
    }

    /** @test */
    public function when_an_instance_is_assigned_to_the_last_person_alphabetically_it_will_wrap_around(): void
    {
        $user_and_team = $this->testUser(['name' => 'Albert Albany']);
        $user1 = $user_and_team['user'];
        $team = $user_and_team['team'];
        $users = User::factory()
            ->hasAttached($team)
            ->count(2)
            ->sequence(
                ['name' => 'Bobby Boston'],
                ['name' => 'Charlie Chicago'],
            )
            ->create();
        $user3 = $users->last();
        $chore = Chore::factory(['frequency_id' => FrequencyType::daily])
            ->for($team)
            ->assignedToTeam()
            ->has(ChoreInstance::factory()->for($user3))
            ->create();

        $chore->complete();

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'user_id' => $user1->id,
            'chore_id' => $chore->id,
            'completed_date' => null,
        ]);
    }

    /** @test */
    public function when_chore_is_completed_in_the_past_the_next_instance_date_is_based_on_that_date(): void
    {
        $date = today();
        $chore = Chore::factory()->withFirstInstance()->create([
            'frequency_id' => FrequencyType::daily,
            'frequency_interval' => 4,
        ]);

        $chore->complete(null, $date->subDays(3));

        $this->assertEquals(
            today()->addDay()->toDateString(),
            $chore->refresh()->nextChoreInstance->due_date->toDateString(),
        );
    }
}
