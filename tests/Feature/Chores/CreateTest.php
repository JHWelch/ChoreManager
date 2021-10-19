<?php

namespace Tests\Feature\Chores;

use App\Enums\Frequency;
use App\Http\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\Team;
use App\Models\User;
use App\Rules\FrequencyDayOf;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function chore_edit_page_can_be_reached()
    {
        // Arrange
        // Create a test user
        $this->testUser();

        // Act
        // Navigate to Chore Create page
        $response = $this->get(route('chores.create'));

        // Assert
        // A page is successfully returned
        $response->assertStatus(200);
    }

    /** @test */
    public function can_create_chore()
    {
        // Arrange
        $user = $this->testUser()['user'];

        // Act
        Livewire::test(Save::class)
            ->set('chore.title', 'Do dishes')
            ->set('chore.description', 'Do the dishes every night.')
            ->set('chore.frequency_id', 1)
            ->call('save');

        // Assert
        $this->assertDatabaseHas((new Chore)->getTable(), [
            'title'        => 'Do dishes',
            'description'  => 'Do the dishes every night.',
            'frequency_id' => Frequency::DAILY,
            'user_id'      => $user->id,
        ]);
    }

    /** @test */
    public function a_user_can_assign_a_chore_to_another_team_member()
    {
        // Arrange
        // Create team with two users, log in with first
        $users         = User::factory()->count(2)->hasTeams()->create();
        $team          = Team::first();
        $assigned_user = $users->pop();
        $chore         = Chore::factory()->raw();

        $this->actingAs($users->first());
        $users->first()->switchTeam($team);

        // Act
        // Create chore, assign to user
        Livewire::test(Save::class)
             ->set('chore.title', $chore['title'])
             ->set('chore.description', $chore['description'])
             ->set('chore.frequency_id', $chore['frequency_id'])
             ->set('chore.user_id', $assigned_user->id)
             ->set('chore_instance.due_date', null)
             ->call('save');

        // Assert
        // The chore is created and assigned to that user
        $this->assertDatabaseHas((new Chore)->getTable(), [
             'user_id'      => $assigned_user->id,
             'title'        => $chore['title'],
             'description'  => $chore['description'],
             'frequency_id' => $chore['frequency_id'],
         ]);
    }

    /** @test */
    public function a_chore_can_be_assigned_to_a_team()
    {
        // Arrange
        // Create user and chore info
        $this->testUser();
        $chore = Chore::factory()->raw();

        // Act
        // Navigate to create chore, create chore without owner
        $component = Livewire::test(Save::class)
            ->set('chore.title', $chore['title'])
            ->set('chore.description', $chore['description'])
            ->set('chore.frequency_id', $chore['frequency_id'])
            ->set('chore.user_id', null)
            ->call('save');

        // Assert
        // Chore is create with no owner.
        $component->assertHasNoErrors();
        $this->assertDatabaseHas((new Chore)->getTable(), [
            'title'        => $chore['title'],
            'description'  => $chore['description'],
            'frequency_id' => $chore['frequency_id'],
            'user_id'      => null,
        ]);
    }

    /** @test */
    public function chores_assigned_to_team_with_due_date_create_instance_assigned_to_team_member()
    {
        // Arrange
        // Create user and chore info
        $user     = $this->testUser()['user'];
        $chore    = Chore::factory()->raw();
        $due_date = today()->addDay(1);

        // Act
        // Navigate to create chore, create chore without owner
        Livewire::test(Save::class)
            ->set('chore.title', $chore['title'])
            ->set('chore.description', $chore['description'])
            ->set('chore.frequency_id', $chore['frequency_id'])
            ->set('chore.user_id', null)
            ->set('chore_instance.due_date', $due_date)
            ->call('save');

        // Assert
        // Chore is create with no owner.
        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'user_id'  => $user->id,
            'due_date' => $due_date,
        ]);
    }

    /** @test */
    public function chores_can_be_created_with_advanced_frequency()
    {
        // Arrange
        // Create user and chore info
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()->raw();

        // Act
        // Navigate to create chore, create chore with advanced frequency
        Livewire::test(Save::class)
            ->set('chore.title', $chore['title'])
            ->set('chore.description', $chore['description'])
            ->set('chore.frequency_id', Frequency::WEEKLY)
            ->set('chore.frequency_interval', 2)
            ->set('chore.frequency_day_of', Carbon::WEDNESDAY)
            ->set('chore.user_id', $user->id)
            ->call('save');

        // Assert
        // Chore is created in database
        $this->assertDatabaseHas((new Chore)->getTable(), [
            'user_id'            => $user->id,
            'frequency_id'       => Frequency::WEEKLY,
            'frequency_interval' => 2,
            'frequency_day_of'   => Carbon::WEDNESDAY,
        ]);
    }

    /**
     * Return a livewire testable already filled with most fields for validating frequency.
     *
     * @return \Livewire\Testing\TestableLivewire
     */
    protected function getFrequencyValidationComponent()
    {
        $this->testUser()['user'];

        return Livewire::test(Save::class);
    }

    /** @test */
    public function chores_with_day_of_week_cannot_be_under_1()
    {
        // Act
        // Set frequency and frequency day of.
        $component = $this->getFrequencyValidationComponent()
            ->set('chore.frequency_id', Frequency::WEEKLY)
            ->call('showDayOfSection')
            ->set('chore.frequency_day_of', 0)
            ->call('save');

        // Assert
        // Has error
        $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
    }

    /** @test */
    public function chores_with_day_of_week_cannot_be_over_7()
    {
        // Act
        // Set frequency and frequency day of.
        $component = $this->getFrequencyValidationComponent()
            ->set('chore.frequency_id', Frequency::WEEKLY)
            ->call('showDayOfSection')
            ->set('chore.frequency_day_of', 8)
            ->call('save');

        // Assert
        // Has error
        $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
    }

    /** @test */
    public function chores_with_day_of_month_cannot_be_under_1()
    {
        // Act
        // Set frequency and frequency day of.
        $component = $this->getFrequencyValidationComponent()
            ->set('chore.frequency_id', Frequency::MONTHLY)
            ->call('showDayOfSection')
            ->set('chore.frequency_day_of', -1)
            ->call('save');

        // Assert
        // Has error
        $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
    }

    /** @test */
    public function chores_with_day_of_month_cannot_be_over_31()
    {
        // Act
        // Set frequency and frequency day of.
        $component = $this->getFrequencyValidationComponent()
            ->set('chore.frequency_id', Frequency::MONTHLY)
            ->call('showDayOfSection')
            ->set('chore.frequency_day_of', 32)
            ->call('save');

        // Assert
        // Has error
        $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
    }

    /** @test */
    public function chores_with_day_of_quarter_cannot_be_under_1()
    {
        // Act
        // Set frequency and frequency day of.
        $component = $this->getFrequencyValidationComponent()
            ->set('chore.frequency_id', Frequency::QUARTERLY)
            ->call('showDayOfSection')
            ->set('chore.frequency_day_of', -1)
            ->call('save');

        // Assert
        // Has error
        $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
    }

    /** @test */
    public function chores_with_day_of_quarter_cannot_be_over_92()
    {
        // Act
        // Set frequency and frequency day of.
        $component = $this->getFrequencyValidationComponent()
            ->set('chore.frequency_id', Frequency::QUARTERLY)
            ->call('showDayOfSection')
            ->set('chore.frequency_day_of', 93)
            ->call('save');

        // Assert
        // Has error
        $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
    }

    /** @test */
    public function chores_with_day_of_year_cannot_be_under_1()
    {
        // Act
        // Set frequency and frequency day of.
        $component = $this->getFrequencyValidationComponent()
            ->set('chore.frequency_id', Frequency::YEARLY)
            ->call('showDayOfSection')
            ->set('chore.frequency_day_of', -1)
            ->call('save');

        // Assert
        // Has error
        $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
    }

    /** @test */
    public function chores_with_day_of_year_cannot_be_over_365()
    {
        // Act
        // Set frequency and frequency day of.
        $component = $this->getFrequencyValidationComponent()
            ->set('chore.frequency_id', Frequency::YEARLY)
            ->call('showDayOfSection')
            ->set('chore.frequency_day_of', 366)
            ->call('save');

        // Assert
        // Has error
        $component->assertHasErrors(['chore.frequency_day_of' => FrequencyDayOf::class]);
    }
}
