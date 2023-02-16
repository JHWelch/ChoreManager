<?php

namespace Tests\Feature\ChoreInstances;

use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function when_a_user_specifies_a_date_while_creating_a_chore_a_chore_instance_is_created(): void
    {
        $this->testUser();
        $date = Carbon::now()->addDays(6);

        Livewire::test(Save::class)
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->set('chore_instance.due_date', $date)
            ->call('save');

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id'       => Chore::first()->id,
            'due_date'       => $date->format('Y-m-d 00:00:00'),
            'completed_date' => null,
        ]);
    }

    /** @test */
    public function a_chore_can_be_created_without_a_date_and_chore_instance(): void
    {
        $this->testUser();

        Livewire::test(Save::class)
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->set('chore_instance.due_date', null)
            ->call('save');

        $this->assertDatabaseCount((new ChoreInstance)->getTable(), 0);
    }

    /** @test */
    public function when_creating_a_chore_with_an_owner_the_chore_instance_has_the_same_owner(): void
    {
        $this->testUser();
        $date = Carbon::now()->addDays(6);
        $user = User::factory()->create();

        Livewire::test(Save::class)
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->set('chore_instance.due_date', $date)
            ->set('chore.user_id', $user->id)
            ->call('save');

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'due_date' => $date->toDateString(),
            'user_id'  => $user->id,
        ]);
    }
}
