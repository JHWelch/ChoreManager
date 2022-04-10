<?php

namespace Tests\Feature\Chores;

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Index;
use App\Models\Chore;
use App\Models\ChoreInstance;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function a_user_can_navigate_to_chores_index()
    {
        $this->testUser();

        $response = $this->get(route('chores.index'));

        $response->assertOk();
    }

    /** @test */
    public function chores_display_on_index_page()
    {
        $user = $this->testUser()['user'];
        Chore::factory()
            ->count(3)
            ->state(new Sequence(
                ['title' => 'Do dishes'],
                ['title' => 'Walk dog'],
                ['title' => 'Do laundry'],
            ))
            ->for($user)
            ->create();

        $component = Livewire::test(Index::class);

        // Assert
        // Assert we can see all the chore titles
        $component->assertSee('Do dishes')
            ->assertSee('Walk dog')
            ->assertSee('Do laundry');
    }

    /*****************************
     * Sorting
     *****************************/

    /** @test */
    public function chores_can_be_sorted_by_title()
    {
        $user = $this->testUser()['user'];
        Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Do dishes'],
                ['title' => 'Walk dog'],
                ['title' => 'Clean car'],
            )
            ->for($user)
            ->create();

        $component = Livewire::test(Index::class)
            ->call('sortBy', 'chores.title');

        $component->assertSeeInOrder(['Clean car', 'Do dishes', 'Walk dog']);
    }

    /** @test */
    public function chores_can_be_sorted_by_frequency()
    {
        $user = $this->testUser()['user'];
        Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Do dishes', 'frequency_id' => Frequency::MONTHLY],
                ['title' => 'Walk dog', 'frequency_id'  => Frequency::DAILY],
                ['title' => 'Clean car', 'frequency_id' => Frequency::WEEKLY],
            )
            ->for($user)
            ->create();

        $component = Livewire::test(Index::class)
            ->call('sortBy', 'chores.frequency_id');

        $component->assertSeeInOrder(['Walk dog', 'Clean car', 'Do dishes']);
    }

    /** @test */
    public function chores_can_be_sorted_by_next_due_date()
    {
        $date1  = today()->addDays(1);
        $date2  = today()->addDays(2);
        $date3  = today()->addDays(3);
        $user   = $this->testUser()['user'];
        $chores = Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Do dishes', 'frequency_id' => Frequency::DAILY],
                ['title' => 'Walk dog', 'frequency_id'  => Frequency::DAILY],
                ['title' => 'Clean car', 'frequency_id' => Frequency::DAILY],
            )
            ->for($user)
            ->create();
        ChoreInstance::factory(['due_date' => $date3])->for($user)->for($chores[0])->create();
        ChoreInstance::factory(['due_date' => $date1])->for($user)->for($chores[1])->create();
        ChoreInstance::factory(['due_date' => $date2])->for($user)->for($chores[2])->create();

        $component = Livewire::test(Index::class)
            ->set('sort', 'chores.title') // Default is due date.
            ->call('sortBy', 'chore_instances.due_date');

        $component->assertSeeInOrder(['Walk dog', 'Clean car', 'Do dishes']);
    }

    /** @test */
    public function chores_can_be_sorted_by_descending_title()
    {
        $user = $this->testUser()['user'];
        Chore::factory()
            ->count(3)
            ->sequence(
                ['title' => 'Do dishes'],
                ['title' => 'Walk dog'],
                ['title' => 'Clean car'],
            )
            ->for($user)
            ->create();

        $component = Livewire::test(Index::class)
            ->call('sortBy', 'chores.title')
            ->call('sortBy', 'chores.title');

        $component->assertSeeInOrder(['Walk dog', 'Do dishes', 'Clean car']);
    }
}
