<?php

namespace Tests\Feature\Chores;

use App\Http\Livewire\Chores\Show;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function can_reach_show_page()
    {
        $chore = Chore::factory()->for($this->testUser()['user'])->create();

        $response = $this->get(route('chores.show', $chore));

        $response->assertOk();
    }

    /** @test */
    public function user_cannot_view_chores_for_another_user()
    {
        $this->testUser();
        $chore = Chore::factory()->forUser()->create();

        $response = $this->get(route('chores.show', ['chore' => $chore]));

        $response->assertForbidden();
    }

    /** @test */
    public function can_see_chore_info_on_chores_show()
    {
        $chore = Chore::factory([
            'title'              => 'Walk the dog.',
            'description'        => 'Do not forget the poop bags.',
            'frequency_id'       => 1,
            'frequency_interval' => 2,
            'user_id'            => $this->testUser()['user']->id,
        ])->create();

        $component = Livewire::test(Show::class, ['chore' => $chore]);

        $component->assertSee('Walk the dog.');
        $component->assertSee('Do not forget the poop bags.');
        $component->assertSee('Repeats every 2 days');
    }

    /** @test */
    public function can_complete_chore_from_chore_page()
    {
        $this->testUser();
        $chore    = Chore::factory()->for($this->user)->withFirstInstance()->create();
        $instance = $chore->nextChoreInstance;

        $component = Livewire::test(Show::class, ['chore' => $chore])
            ->call('complete');

        $instance->refresh();
        $this->assertEquals(true, $instance->is_completed);
        $component->assertRedirect('/');
    }

    /** @test */
    public function can_see_chore_history()
    {
        $user1 = $this->testUser()['user'];
        $user2 = User::factory()->create();
        $chore = Chore::factory()
            ->for($this->user)
            ->has(
                ChoreInstance::factory()->count(3)->sequence(
                    [
                        'completed_date'  => today()->subDays(1),
                        'user_id'         => $user1->id,
                        'completed_by_id' => $user1->id,
                    ],
                    [
                        'completed_date'  => today()->subDays(2),
                        'user_id'         => $user2->id,
                        'completed_by_id' => $user2->id,
                    ],
                    [
                        'completed_date'  => today()->subDays(3),
                        'user_id'         => $user1->id,
                        'completed_by_id' => $user1->id,
                    ],
                )
            )
            ->create();

        $component = Livewire::test(Show::class, ['chore' => $chore]);

        $component->assertSeeInOrder([
            $user1->name,
            'completed chore',
            'yesterday',
            $user2->name,
            'completed chore',
            '2 days ago',
            $user1->name,
            'completed chore',
            '3 days ago',
        ]);
    }

    /** @test */
    public function chores_assigned_to_team_display_team_as_owner()
    {
        $team  = $this->testUser()['team'];
        $chore = Chore::factory([
            'title' => 'Walk the dog',
        ])
            ->assignedToTeam()
            ->for($team)
            ->create();

        $component = Livewire::test(Show::class, ['chore' => $chore]);

        $component->assertSeeInOrder([
            'Owner',
            $team->name,
        ]);
    }

    /** @test */
    public function can_complete_chore_for_another_another_team_user()
    {
        $this->testUser();
        $other_user = User::factory()->hasAttached($this->team)->create();

        $chore = Chore::factory()
            ->for($this->team)
            ->for($other_user)
            ->withFirstInstance()
            ->create();

        Livewire::test(Show::class, [
            'chore' => $chore,
        ])
            ->set('user_id', $other_user->id)
            ->call('customComplete');

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id'        => $chore->id,
            'completed_date'  => today(),
            'completed_by_id' => $other_user->id,
        ]);
    }

    /** @test */
    public function can_complete_chore_on_a_past_date()
    {
        $user  = $this->testUser()['user'];
        $date  = today()->subDays(2);
        $chore = Chore::factory()
            ->for($user)
            ->withFirstInstance()
            ->create();

        $component = Livewire::test(Show::class, [
            'chore' => $chore,
        ])
            ->set('completed_date', $date)
            ->call('customComplete');

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'chore_id'        => $chore->id,
            'completed_date'  => $date,
            'completed_by_id' => $user->id,
        ]);
        $component->assertRedirect('/');
    }
}
