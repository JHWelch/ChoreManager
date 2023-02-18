<?php

namespace Tests\Feature\Chores;

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function chore_edit_page_can_be_reached(): void
    {
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->create();

        $response = $this->get(route('chores.edit', ['chore' => $chore->id]));

        $response->assertOk();
    }

    /** @test */
    public function user_cannot_edit_chores_for_another_user(): void
    {
        $this->testUser();
        $chore = Chore::factory()->forUser()->create();

        $response = $this->get(route('chores.edit', ['chore' => $chore]));

        $response->assertForbidden();
    }

    /** @test */
    public function existing_chore_screen_shows_its_information(): void
    {
        $user  = $this->testUser()['user'];
        $chore = Chore::create([
            'user_id'      => $user->id,
            'title'        => 'Do dishes',
            'description'  => 'Do dishes every night.',
            'frequency_id' => Frequency::DAILY,
        ]);

        $component = Livewire::test(Save::class, ['chore' => $chore]);

        $component->assertSet('chore.title', 'Do dishes');
        $component->assertSet('chore.description', 'Do dishes every night.');
        $component->assertSet('chore.frequency_id', 1);
    }

    /** @test */
    public function a_chore_can_be_updated_after_it_is_created(): void
    {
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->create();

        Livewire::test(Save::class, ['chore' => $chore])
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->call('save');

        $this->assertDatabaseHas((new Chore)->getTable(), [
            'title'        => 'Do dishes',
            'description'  => 'Do the dishes every night.',
            'frequency_id' => Frequency::DAILY,
            'user_id'      => $user->id,
        ]);
    }
}
