<?php

namespace Tests\Feature\Chores;

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function chore_edit_page_can_be_reached()
    {
        // Arrange
        // Create a chore
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->create();

        // Act
        // Navigate to its page
        $response = $this->get(route('chores.edit', ['chore' => $chore->id]));

        // Assert
        // A page is successfully returned
        $response->assertStatus(200);
    }

    /** @test */
    public function existing_chore_screen_shows_its_information()
    {
        // Arrange
        // Create a user and make a chore
        $user  = $this->testUser()['user'];
        $chore = Chore::create([
            'user_id'      => $user->id,
            'title'        => 'Do dishes',
            'description'  => 'Do dishes every night.',
            'frequency_id' => Frequency::DAILY,
        ]);

        // Act
        // Go to Edit chore screen
        $component = Livewire::test(Save::class, ['chore' => $chore]);

        // Assert
        // The details are present from the created chore
        $component->assertSet('chore.title', 'Do dishes');
        $component->assertSet('chore.description', 'Do dishes every night.');
        $component->assertSet('chore.frequency_id', 1);
    }

    /** @test */
    public function a_chore_can_be_updated_after_it_is_created()
    {
        // Arrange
        // Create a user and their chore
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->for($user)->create();

        // Act
        // Navigate to edit screen and change some details and save it
        Livewire::test(Save::class, ['chore' => $chore])
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->call('save');

        // Assert
        // The changes have persisted in the database
        $this->assertDatabaseHas((new Chore)->getTable(), [
            'title'        => 'Do dishes',
            'description'  => 'Do the dishes every night.',
            'frequency_id' => Frequency::DAILY,
            'user_id'      => $user->id,
        ]);
    }
}
