<?php

namespace Tests\Feature\Chores;

use App\Enums\FrequencyType;
use App\Livewire\Chores\Save;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\Team;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Tests\TestCase;

class CreateTest extends TestCase
{
    /** @test */
    public function chore_edit_page_can_be_reached(): void
    {
        $this->testUser();

        $response = $this->get(route('chores.create'));

        $response->assertOk();
    }

    /** @test */
    public function can_create_chore(): void
    {
        $user = $this->testUser()['user'];

        Livewire::test(Save::class)
            ->set('form.title', 'Do dishes')
            ->set('form.description', 'Do the dishes every night.')
            ->set('form.frequency_id', FrequencyType::daily->value)
            ->call('save');

        $this->assertDatabaseHas((new Chore)->getTable(), [
            'title' => 'Do dishes',
            'description' => 'Do the dishes every night.',
            'frequency_id' => FrequencyType::daily,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function a_user_can_assign_a_chore_to_another_team_member(): void
    {
        $users = User::factory()->count(2)->hasTeams()->create();
        $team = Team::first();
        $assigned_user = $users->pop();
        $chore = Chore::factory()->raw();
        $this->actingAs($users->first());
        $users->first()->switchTeam($team);

        Livewire::test(Save::class)
            ->set('form.title', $chore['title'])
            ->set('form.description', $chore['description'])
            ->set('form.frequency_id', $chore['frequency_id']->value)
            ->set('form.chore_user_id', $assigned_user->id)
            ->set('form.due_date', null)
            ->call('save');

        $this->assertDatabaseHas((new Chore)->getTable(), [
            'user_id' => $assigned_user->id,
            'title' => $chore['title'],
            'description' => $chore['description'],
            'frequency_id' => $chore['frequency_id'],
        ]);
    }

    /** @test */
    public function a_chore_can_be_assigned_to_a_team(): void
    {
        $this->testUser();
        $chore = Chore::factory()->raw();

        $component = Livewire::test(Save::class)
            ->set('form.title', $chore['title'])
            ->set('form.description', $chore['description'])
            ->set('form.frequency_id', $chore['frequency_id']->value)
            ->set('form.chore_user_id', null)
            ->call('save');

        $component->assertHasNoErrors();
        $this->assertDatabaseHas((new Chore)->getTable(), [
            'title' => $chore['title'],
            'description' => $chore['description'],
            'frequency_id' => $chore['frequency_id'],
            'user_id' => null,
        ]);
    }

    /** @test */
    public function chores_assigned_to_team_with_due_date_create_instance_assigned_to_team_member(): void
    {
        $user = $this->testUser()['user'];
        $chore = Chore::factory()->raw();
        $due_date = today()->addDay(1);

        Livewire::test(Save::class)
            ->set('form.title', $chore['title'])
            ->set('form.description', $chore['description'])
            ->set('form.frequency_id', $chore['frequency_id']->value)
            ->set('form.chore_user_id', null)
            ->set('form.due_date', $due_date)
            ->call('save');

        $this->assertDatabaseHas((new ChoreInstance)->getTable(), [
            'user_id' => $user->id,
            'due_date' => $due_date,
        ]);
    }

    /** @test */
    public function chores_can_be_created_with_advanced_frequency(): void
    {
        $user = $this->testUser()['user'];
        $chore = Chore::factory()->raw();

        Livewire::test(Save::class)
            ->set('form.title', $chore['title'])
            ->set('form.description', $chore['description'])
            ->set('form.frequency_id', FrequencyType::weekly->value)
            ->set('form.frequency_interval', 2)
            ->set('form.frequency_day_of', Carbon::WEDNESDAY)
            ->set('form.chore_user_id', $user->id)
            ->call('save');

        $this->assertDatabaseHas((new Chore)->getTable(), [
            'user_id' => $user->id,
            'frequency_id' => FrequencyType::weekly->value,
            'frequency_interval' => 2,
            'frequency_day_of' => Carbon::WEDNESDAY,
        ]);
    }

    /**
     * Return a livewire testable already filled with most fields for validating frequency.
     */
    protected function getFrequencyValidationComponent(): Testable
    {
        $this->testUser();

        return Livewire::test(Save::class);
    }

    /** @test */
    public function chores_with_day_of_week_cannot_be_under_1(): void
    {
        $component = $this->getFrequencyValidationComponent()
            ->set('form.frequency_id', FrequencyType::weekly->value)
            ->call('showDayOfSection')
            ->set('form.frequency_day_of', 0)
            ->call('save');

        $component->assertHasErrors([
            'form.frequency_day_of' => 'Day of the Weeks must be between 1 and 7.',
        ]);
    }

    /** @test */
    public function chores_with_day_of_week_cannot_be_over_7(): void
    {
        $component = $this->getFrequencyValidationComponent()
            ->set('form.frequency_id', FrequencyType::weekly->value)
            ->call('showDayOfSection')
            ->set('form.frequency_day_of', 8)
            ->call('save');

        $component->assertHasErrors([
            'form.frequency_day_of' => 'Day of the Weeks must be between 1 and 7.',
        ]);
    }

    /** @test */
    public function chores_with_day_of_month_cannot_be_under_1(): void
    {
        $component = $this->getFrequencyValidationComponent()
            ->set('form.frequency_id', FrequencyType::monthly->value)
            ->call('showDayOfSection')
            ->set('form.frequency_day_of', -1)
            ->call('save');

        $component->assertHasErrors([
            'form.frequency_day_of' => 'Day of the Months must be between 1 and 31.',
        ]);
    }

    /** @test */
    public function chores_with_day_of_month_cannot_be_over_31(): void
    {
        $component = $this->getFrequencyValidationComponent()
            ->set('form.frequency_id', FrequencyType::monthly->value)
            ->call('showDayOfSection')
            ->set('form.frequency_day_of', 32)
            ->call('save');

        $component->assertHasErrors([
            'form.frequency_day_of' => 'Day of the Months must be between 1 and 31.',
        ]);
    }

    /** @test */
    public function chores_with_day_of_quarter_cannot_be_under_1(): void
    {
        $component = $this->getFrequencyValidationComponent()
            ->set('form.frequency_id', FrequencyType::quarterly->value)
            ->call('showDayOfSection')
            ->set('form.frequency_day_of', -1)
            ->call('save');

        $component->assertHasErrors([
            'form.frequency_day_of' => 'Day of the Quarters must be between 1 and 92.',
        ]);
    }

    /** @test */
    public function chores_with_day_of_quarter_cannot_be_over_92(): void
    {
        $component = $this->getFrequencyValidationComponent()
            ->set('form.frequency_id', FrequencyType::quarterly->value)
            ->call('showDayOfSection')
            ->set('form.frequency_day_of', 93)
            ->call('save');

        $component->assertHasErrors([
            'form.frequency_day_of' => 'Day of the Quarters must be between 1 and 92.',
        ]);
    }

    /** @test */
    public function chores_with_day_of_year_cannot_be_under_1(): void
    {
        $component = $this->getFrequencyValidationComponent()
            ->set('form.frequency_id', FrequencyType::yearly->value)
            ->call('showDayOfSection')
            ->set('form.frequency_day_of', -1)
            ->call('save');

        $component->assertHasErrors([
            'form.frequency_day_of' => 'Day of the Years must be between 1 and 365.',
        ]);
    }

    /** @test */
    public function chores_with_day_of_year_cannot_be_over_365(): void
    {
        $component = $this->getFrequencyValidationComponent()
            ->set('form.frequency_id', FrequencyType::yearly->value)
            ->call('showDayOfSection')
            ->set('form.frequency_day_of', 366)
            ->call('save');

        $component->assertHasErrors([
            'form.frequency_day_of' => 'Day of the Years must be between 1 and 365.',
        ]);
    }

    /** @test */
    public function when_you_change_to_daily_frequency_day_of_is_disabled(): void
    {
        $this->testUser();
        $chore = Chore::factory()->raw();
        $component = Livewire::test(Save::class)
            ->set('form.title', $chore['title'])
            ->set('form.description', $chore['description'])
            ->set('form.frequency_id', FrequencyType::monthly->value)
            ->set('form.frequency_day_of', 5)
            ->set('show_on', true);

        $component->set('form.frequency_id', FrequencyType::daily->value);

        $component->assertSet('show_on', false);
        $component->assertSet('form.frequency_day_of', null);
    }

    /** @test */
    public function when_you_change_to_does_not_repeat_frequency_day_of_is_disabled(): void
    {
        $this->testUser();
        $chore = Chore::factory()->raw();
        $component = Livewire::test(Save::class)
            ->set('form.title', $chore['title'])
            ->set('form.description', $chore['description'])
            ->set('form.frequency_id', FrequencyType::monthly->value)
            ->set('form.frequency_day_of', 5)
            ->set('show_on', true);

        $component->set('form.frequency_id', FrequencyType::doesNotRepeat->value);

        $component->assertSet('show_on', false);
        $component->assertSet('form.frequency_day_of', null);
    }

    /** @test */
    public function when_updating_to_another_frequency_id_frequency_day_of_changes_to_1(): void
    {
        $this->testUser();
        $chore = Chore::factory()->raw();
        $component = Livewire::test(Save::class)
            ->set('form.title', $chore['title'])
            ->set('form.description', $chore['description'])
            ->set('form.frequency_id', FrequencyType::yearly->value)
            ->set('form.frequency_day_of', 130)
            ->set('show_on', true);

        $component->set('form.frequency_id', FrequencyType::monthly->value);

        $component->assertSet('show_on', true);
        $component->assertSet('form.frequency_day_of', 1);
    }

    /** @test */
    public function does_not_repeat_does_not_show_interval_input(): void
    {
        $this->testUser();
        $component = Livewire::test(Save::class)
            ->set('form.frequency_id', FrequencyType::doesNotRepeat->value);

        $component->assertDontSee('Every');
    }
}
