<?php

namespace Tests\Feature\ChoreInstances;

use App\Http\Livewire\ChoreInstances\IndexLine;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class Snooze extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_nooze_a_chore_one_day()
    {
        // Arrange
        // Create user and chore
        $now   = Carbon::now();
        $user  = $this->testUser();
        $chore = Chore::factory()
            ->for($user)
            ->has(ChoreInstance::factory([
                'due_date' => $now,
            ]))
            ->create();

        // Act
        // Open Index Line and Snooze for one day.
        Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('snoozeDay');

        // Assert
        // The chore instance has moved one day.
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $chore->id,
            'due_date' => Carbon::parse($now->addDay()->toDateString()),
        ]);
    }
}
