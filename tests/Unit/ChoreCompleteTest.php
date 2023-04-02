<?php

namespace Tests\Unit;

use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChoreCompleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_there_is_a_next_instance_completes_instance(): void
    {
        $this->testUser();
        $chore         = Chore::factory()->withFirstInstance()->daily()->create();
        $firstInstance = $chore->nextInstance;

        $chore->complete();

        $firstInstance->refresh();
        $this->assertTrue($firstInstance->is_completed);
        $this->assertDatabaseCount((new ChoreInstance)->getTable(), 2);
    }

    /** @test */
    public function can_complete_a_chore_without_a_chore_instance(): void
    {
        $this->testUser();
        $chore = Chore::factory()->for($this->user)->daily()->create();

        $chore->complete();

        $now           = now()->toDateString();
        $choreInstance = ChoreInstance::first();
        $this->assertNotNull($choreInstance);
        $this->assertEquals($chore->id, $choreInstance->chore_id);
        $this->assertTrue($choreInstance->is_completed);
        $this->assertEquals($now, $choreInstance->due_date->toDateString());
        $this->assertEquals($now, $choreInstance->completed_date->toDateString());
        $this->assertEquals($this->user->id, $choreInstance->user_id);
        $this->assertEquals($this->user->id, $choreInstance->completed_by_id);
    }

    /** @test */
    public function chore_can_be_completed_at_another_time(): void
    {
        $this->testUser();
        $chore = Chore::factory()->for($this->user)->daily()->create();
        $date  = now()->subDays(3);

        $chore->complete(on: $date);

        $choreInstance = ChoreInstance::first();
        $this->assertNotNull($choreInstance);
        $this->assertEquals($date->toDateString(), $choreInstance->due_date->toDateString());
        $this->assertEquals($date->toDateString(), $choreInstance->completed_date->toDateString());
    }

    /** @test */
    public function chore_can_be_completed_for_another_user(): void
    {
        $this->testUser();
        $user  = User::factory()->hasAttached($this->team)->create();
        $chore = Chore::factory()->for($this->user)->daily()->create();

        $chore->complete(for: $user->id);

        $choreInstance = ChoreInstance::first();
        $this->assertNotNull($choreInstance);
        $this->assertEquals($user->id, $choreInstance->user_id);
        $this->assertEquals($user->id, $choreInstance->completed_by_id);
    }
}
