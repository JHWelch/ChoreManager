<?php

namespace Tests\Feature\Api\Chores;

use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function calling_update_with_complete_flag_completes_current_instance()
    {
        $this->testUser();
        $chore          = Chore::factory()->for($this->user)->create();
        $chore_instance = ChoreInstance::factory()
            ->for($chore)
            ->create();

        $response = $this->patch(route('api.chores.update', ['chore' => $chore]),
            ['completed' => true],
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertTrue($chore_instance->refresh()->is_completed);
    }

    /** @test */
    public function user_can_complete_chore_for_their_team()
    {
        $this->testUser();
        $chore          = Chore::factory()->for($this->team)->create();
        $chore_instance = ChoreInstance::factory()
            ->for($chore)
            ->create();

        $response = $this->patch(route('api.chores.update', ['chore' => $chore]),
            ['completed' => true],
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertTrue($chore_instance->refresh()->is_completed);
    }

    /** @test */
    public function user_cannot_complete_chores_they_do_not_own()
    {
        $this->testUser();
        $chore          = Chore::factory()->create();
        $chore_instance = ChoreInstance::factory()
            ->for($chore)
            ->create();

        $response = $this->patch(route('api.chores.update', ['chore' => $chore]),
            ['completed' => true],
        );

        $response->assertForbidden();
        $this->assertFalse($chore_instance->refresh()->is_completed);
    }
}
