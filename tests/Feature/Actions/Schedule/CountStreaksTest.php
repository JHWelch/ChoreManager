<?php

namespace Tests\Feature\Actions\Schedule;

use App\Actions\Schedule\CountStreaks;
use App\Models\Chore;
use App\Models\StreakCount;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CountStreaksTest extends TestCase
{
    protected function uncompletedChoreForUser($user)
    {
        Chore::factory()
            ->for($user)
            ->withFirstInstance((new Carbon)->subDay(), $user->id)
            ->create();
    }

    protected function uncompletedChoreForTeam($team)
    {
        Chore::factory()
            ->for($team)
            ->withFirstInstance((new Carbon)->subDay(), $team->users()->first()->id)
            ->create();
    }

    /** @test */
    public function it_creates_streaks_for_users_who_have_not_started_one(): void
    {
        $user = User::factory()->create();

        (new CountStreaks)();

        $this->assertDatabaseHas((new StreakCount)->getTable(), [
            'team_id' => null,
            'user_id' => $user->id,
            'count' => 1,
            'ended_at' => null,
        ]);
    }

    /** @test */
    public function it_will_not_create_streak_if_user_has_unfinished_chores(): void
    {
        $user = User::factory()->create();
        $this->uncompletedChoreForUser($user);

        (new CountStreaks)();

        $this->assertDatabaseCount((new StreakCount)->getTable(), 0);
    }

    /** @test */
    public function it_increments_current_streaks_for_users(): void
    {
        $user = User::factory()->create();
        $streak = StreakCount::factory()
            ->for($user)
            ->create(['count' => 5]);

        (new CountStreaks)();

        $this->assertDatabaseHas((new StreakCount)->getTable(), [
            'id' => $streak->id,
            'team_id' => null,
            'user_id' => $user->id,
            'count' => 6,
            'ended_at' => null,
        ]);
    }

    /** @test */
    public function it_will_not_increment_streak_if_user_has_unfinished_chores(): void
    {
        $user = User::factory()->create();
        $streak = StreakCount::factory()
            ->for($user)
            ->create(['count' => 5]);
        $this->uncompletedChoreForUser($user);

        (new CountStreaks)();

        $this->assertDatabaseHas((new StreakCount)->getTable(), [
            'id' => $streak->id,
            'team_id' => null,
            'user_id' => $user->id,
            'count' => 5,
        ]);
    }

    /** @test */
    public function it_ends_streak_if_user_has_uncompleted_chores(): void
    {
        $user = User::factory()->create();
        $streak = StreakCount::factory()
            ->for($user)
            ->create(['count' => 5]);
        $this->uncompletedChoreForUser($user);

        (new CountStreaks)();

        $streak->refresh();
        $this->assertNotNull($streak->ended_at);
        $this->assertEquals($streak->count, 5);
    }

    /** @test */
    public function it_creates_streaks_for_teams_who_have_not_started_one(): void
    {
        $team = Team::factory()->hasUsers()->create();

        (new CountStreaks)();

        $this->assertDatabaseHas((new StreakCount)->getTable(), [
            'user_id' => null,
            'team_id' => $team->id,
            'count' => 1,
            'ended_at' => null,
        ]);
    }

    /** @test */
    public function it_will_not_create_streak_if_team_has_unfinished_chores(): void
    {
        $team = Team::factory()->hasUsers()->create();
        $this->uncompletedChoreForTeam($team);

        (new CountStreaks)();

        $this->assertDatabaseMissing((new StreakCount)->getTable(), [
            'team_id' => $team->id,
        ]);
    }

    /** @test */
    public function it_increments_current_streaks_for_teams(): void
    {
        $team = Team::factory()->hasUsers()->create();
        $streak = StreakCount::factory()
            ->for($team)
            ->create(['count' => 5]);

        (new CountStreaks)();

        $this->assertDatabaseHas((new StreakCount)->getTable(), [
            'id' => $streak->id,
            'team_id' => $team->id,
            'count' => 6,
            'ended_at' => null,
        ]);
    }

    /** @test */
    public function it_will_not_increment_streak_if_team_has_unfinished_chores(): void
    {
        $team = Team::factory()->hasUsers()->create();
        $streak = StreakCount::factory()
            ->for($team)
            ->create(['count' => 5]);
        $this->uncompletedChoreForTeam($team);

        (new CountStreaks)();

        $this->assertDatabaseHas((new StreakCount)->getTable(), [
            'id' => $streak->id,
            'team_id' => $team->id,
            'count' => 5,
        ]);
    }

    /** @test */
    public function it_ends_streak_if_team_has_uncompleted_chores(): void
    {
        $team = Team::factory()->hasUsers()->create();
        $streak = StreakCount::factory()
            ->for($team)
            ->create(['count' => 5]);
        $this->uncompletedChoreForTeam($team);

        (new CountStreaks)();

        $streak->refresh();
        $this->assertNotNull($streak->ended_at);
        $this->assertEquals($streak->count, 5);
    }

    /** @test */
    public function it_sets_time_stamps_correctly_for_user(): void
    {
        $user = User::factory()->create();

        (new CountStreaks)();

        $this->assertDatabaseMissing((new StreakCount)->getTable(), [
            'user_id' => $user->id,
            'created_at' => null,
            'updated_at' => null,
        ]);
    }

    /** @test */
    public function it_sets_time_stamps_correctly_for_team(): void
    {
        $team = Team::factory()->hasUsers()->create();

        (new CountStreaks)();

        $this->assertDatabaseMissing((new StreakCount)->getTable(), [
            'team_id' => $team->id,
            'created_at' => null,
            'updated_at' => null,
        ]);
    }

    /** @test */
    public function it_updates_timestamp_on_increment(): void
    {
        $user = User::factory()->create();
        $streak = StreakCount::factory()
            ->for($user)
            ->create(['count' => 5]);
        Carbon::setTestNow(Carbon::now()->addDay());

        (new CountStreaks)();

        $this->assertDatabaseMissing((new StreakCount)->getTable(), [
            'id' => $streak->id,
            'updated_at' => $streak->updated_at,
        ]);
    }
}
