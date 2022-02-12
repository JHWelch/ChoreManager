<?php

namespace Tests\Feature\Actions\Schedule;

use App\Actions\Schedule\CountStreaks;
use App\Models\StreakCount;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CountStreaksTest extends TestCase
{
    use LazilyRefreshDatabase;

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
}
