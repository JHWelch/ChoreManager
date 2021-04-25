<?php

namespace Tests\Feature\ChoreInstances;

use App\Enums\Frequency;
use App\Http\Livewire\ChoreInstances\IndexLine;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexLineTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_complete_a_chore_instance()
    {
        // Arrange
        // Create user and a chore Instance
        $user           = $this->testUser()['user'];
        $chore          = Chore::factory()->for($user)->withFirstInstance()->create();
        $chore_instance = $chore->nextChoreInstance;

        // Act
        // Open chore instance on line and complete it
        Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('complete');

        // Assert
        // It is completed
        $chore_instance->refresh();

        $this->assertTrue($chore_instance->is_completed);
    }

    /** @test */
    public function when_a_chore_instance_is_completed_a_new_one_is_created_daily()
    {
        // Arrange
        // Create a chore with an instance
        $now   = today();
        $user  = $this->testUser()['user'];
        $chore = Chore::factory(
            ['frequency_id' => Frequency::DAILY]
        )
            ->for($user)
            ->withFirstInstance($now)
            ->create();

        // Act
        // Navigate to line and click complete
        Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('complete');

        // Assert
        // Check to see if a chore was created the next day.
        $chore->refresh();

        $this->assertEquals(
            $now->addDay()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function can_complete_chore_for_another_another_team_user()
    {
        // Arrange
        // Create acting as user and another user in the same team and chore.
        $this->testUser();
        $other_user = User::factory()->hasAttached($this->team)->create();

        $chore = Chore::factory()
            ->for($other_user)
            ->withFirstInstance()
            ->create();

        // Act
        // Complete the chore for the other user
        Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])
            ->set('user_id', $other_user->id)
            ->call('completeForUser');

        // Assert
        // The chore is completed and completed by the user
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id'        => $chore->id,
            'completed_date'  => today(),
            'completed_by_id' => $other_user->id,
        ]);
    }
}
