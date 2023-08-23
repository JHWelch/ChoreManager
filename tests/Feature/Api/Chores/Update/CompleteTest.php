<?php

namespace Tests\Feature\Api\Chores\Update;

use App\Models\Chore;
use App\Models\ChoreInstance;
use Tests\TestCase;

class CompleteTest extends TestCase
{
    protected function callCompleteEndpoint(Chore $chore)
    {
        return $this->patch(
            route('api.chores.update', ['chore' => $chore]),
            ['completed' => true],
        );
    }

    /** @test */
    public function calling_update_with_complete_flag_completes_current_instance(): void
    {
        $this->testUser();
        $chore = Chore::factory()->for($this->user)->create();
        $chore_instance = ChoreInstance::factory()
            ->for($chore)
            ->create();

        $response = $this->callCompleteEndpoint($chore);

        $response->assertOk();
        $this->assertTrue($chore_instance->refresh()->is_completed);
    }

    /** @test */
    public function user_can_complete_chore_for_their_team(): void
    {
        $this->testUser();
        $chore = Chore::factory()->for($this->team)->create();
        $chore_instance = ChoreInstance::factory()
            ->for($chore)
            ->create();

        $response = $this->callCompleteEndpoint($chore);

        $response->assertOk();
        $this->assertTrue($chore_instance->refresh()->is_completed);
    }

    /** @test */
    public function user_cannot_complete_chores_they_do_not_own(): void
    {
        $this->testUser();
        $chore = Chore::factory()->create();
        $chore_instance = ChoreInstance::factory()
            ->for($chore)
            ->create();

        $response = $this->callCompleteEndpoint($chore);

        $response->assertForbidden();
        $this->assertFalse($chore_instance->refresh()->is_completed);
    }

    /** @test */
    public function chore_is_returned_with_new_information(): void
    {
        $this->testUser();
        $chore = Chore::factory()
            ->for($this->user)
            ->daily()
            ->withFirstInstance()
            ->create();

        $response = $this->callCompleteEndpoint($chore);
        $chore->refresh();

        $response->assertJson(['data' => [
            'id' => $chore->id,
            'user_id' => $chore->user_id,
            'title' => $chore->title,
            'description' => $chore->description,
            'team_id' => $chore->team_id,
            'frequency_id' => $chore->frequency_id->value,
            'frequency_interval' => $chore->frequency_interval,
            'frequency_day_of' => $chore->frequency_day_of,
            'created_at' => $chore->created_at->toIsoString(),
            'updated_at' => $chore->updated_at->toIsoString(),
            'next_due_user_id' => $chore->nextChoreInstance?->user_id,
            'next_due_date' => $chore->next_due_date->toDateString(),
            'due_date_updated_at' => $chore->due_date_updated_at->toIsoString(),
        ]]);
    }
}
