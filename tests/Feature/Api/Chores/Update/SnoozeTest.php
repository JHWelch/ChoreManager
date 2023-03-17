<?php

namespace Tests\Feature\Api\Chores\Update;

use App\Models\Chore;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class SnoozeTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function snoozeEndpoint(Chore $chore, Carbon $nextDueDate)
    {
        return $this->patch(
            route('api.chores.update', ['chore' => $chore]),
            ['next_due_date' => $nextDueDate->toDateString()],
        );
    }

    /** @test */
    public function user_can_snooze_chore()
    {
        $this->testUser();
        $chore = Chore::factory()
            ->for($this->user)
            ->withFirstInstance()
            ->create();
        $date = Carbon::now()->addDays(3);

        $response = $this->snoozeEndpoint($chore, $date);

        $response->assertOk();
        $this->assertEquals(
            $date->toDateString(),
            $chore->refresh()->next_due_date->toDateString()
        );
    }
}
