<?php

namespace Tests\Feature\ChoreInstances;

use App\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Livewire\Livewire;
use Tests\TestCase;

class EditTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function when_updating_chore_instance_with_null_date_create_chore_instance(): void
    {
        $user = $this->testUser()['user'];
        $chore = Chore::factory()
            ->for($user)
            ->create();
        $date = $this->faker->dateTimeBetween('+0 days', '+1 year');

        Livewire::test(Save::class, ['chore' => $chore])
            ->set('form.due_date', Carbon::parse($date))
            ->call('save');

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $chore->id,
            'due_date' => $date->format('Y-m-d 00:00:00'),
        ]);
    }

    /** @test */
    public function when_removing_the_due_date_from_a_chore_it_will_delete_the_chore_instance(): void
    {
        $user = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->withFirstInstance()->create();

        $livewire = Livewire::test(Save::class, ['chore' => $chore])
            ->set('form.due_date', null)
            ->assertSet('form.due_date', null)
            ->call('save');

        $this->assertDatabaseEmpty((new ChoreInstance)->getTable());
    }

    /** @test */
    public function when_opening_chore_edit_due_date_is_populated(): void
    {
        $this->testUser();
        $date = today()->addDays(5);
        $chore = Chore::factory()->for($this->user)->withFirstInstance($date)->create();

        $component = Livewire::test(Save::class, ['chore' => $chore]);

        $component->assertSet('form.due_date', $date->startOfDay()->format('Y-m-d'));
    }

    /** @test */
    public function after_completing_a_chore_you_can_see_next_chore_instance_date(): void
    {
        $this->testUser();
        $date = Carbon::now();
        $chore = Chore::factory()
            ->withFirstInstance($date)
            ->for($this->user)
            ->daily()
            ->create();
        $chore->complete();
        $chore->refresh();

        $component = Livewire::test(Save::class, ['chore' => $chore]);

        $component->assertSet('form.due_date', $date->addDay()->startOfDay()->format('Y-m-d'));
    }

    /** @test */
    public function a_chore_instance_can_be_assigned_to_a_new_user(): void
    {
        $this->testUser();
        $user = User::factory()->hasAttached($this->team)->create();
        $chore = Chore::factory()
            ->for($user)
            ->for($this->team)
            ->withFirstInstance()
            ->create();

        Livewire::test(Save::class, ['chore' => $chore])
            ->set('form.instance_user_id', $user->id)
            ->call('save');

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id' => $chore->id,
            'user_id' => $user->id,
        ]);
    }
}
