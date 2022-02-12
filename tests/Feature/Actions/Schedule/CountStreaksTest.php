<?php

namespace Tests\Feature\Actions\Schedule;

use App\Actions\Schedule\CountStreaks;
use App\Models\Chore;
use App\Models\StreakCount;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CountStreaksTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function uncompletedChore($user)
    {
        Chore::factory()
            ->for($user)
            ->withFirstInstance((new Carbon)->subDay(), $user->id)
            ->create();
    }

    /** @test */
    public function it_creates_streaks_for_users_who_have_not_started_one()
    {
        $user = User::factory()->create();

        (new CountStreaks)();

        $this->assertDatabaseHas((new StreakCount)->getTable(), [
            'user_id'  => $user->id,
            'count'    => 1,
            'ended_at' => null,
        ]);
    }

    /** @test */
    public function it_will_not_create_streak_if_user_has_unfinished_chores()
    {
        $user = User::factory()->create();
        $this->uncompletedChore($user);

        (new CountStreaks)();

        $this->assertDatabaseCount((new StreakCount)->getTable(), 0);
    }

    /** @test */
    public function it_increments_current_streaks()
    {
        $user   = User::factory()->create();
        $streak = StreakCount::factory()
            ->for($user)
            ->create(['count' => 5]);

        (new CountStreaks)();

        $this->assertDatabaseHas((new StreakCount)->getTable(), [
            'id'       => $streak->id,
            'user_id'  => $user->id,
            'count'    => 6,
            'ended_at' => null,
        ]);
    }

    /** @test */
    public function it_will_not_increment_streak_if_user_has_unfinished_chores()
    {
        $user   = User::factory()->create();
        $streak = StreakCount::factory()
            ->for($user)
            ->create(['count' => 5]);
        $this->uncompletedChore($user);

        (new CountStreaks)();

        $this->assertDatabaseHas((new StreakCount)->getTable(), [
            'id'       => $streak->id,
            'user_id'  => $user->id,
            'count'    => 5,
        ]);
    }

    /** @test */
    public function it_ends_streak_if_user_has_uncompleted_chores()
    {
        $user   = User::factory()->create();
        $streak = StreakCount::factory()
            ->for($user)
            ->create(['count' => 5]);
        $this->uncompletedChore($user);

        (new CountStreaks)();

        $streak->refresh();
        $this->assertNotNull($streak->ended_at);
        $this->assertEquals($streak->count, 5);
    }
}
