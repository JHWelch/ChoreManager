<?php

namespace Tests\Unit;

use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChoreCompleteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_there_is_a_next_instance_completes_instance(): void
    {
        $this->testUser();
        $chore = Chore::factory()->withFirstInstance()->daily()->create();
        $firstInstance = $chore->nextInstance;

        $chore->complete();

        $firstInstance->refresh();
        $this->assertTrue($firstInstance->is_completed);
        $this->assertDatabaseCount((new ChoreInstance)->getTable(), 2);
    }
}
