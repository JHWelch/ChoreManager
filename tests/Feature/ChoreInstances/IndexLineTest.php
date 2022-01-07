<?php

namespace Tests\Feature\ChoreInstances;

use App\Enums\Frequency;
use App\Http\Livewire\ChoreInstances\IndexLine;
use App\Models\Chore;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;
use YlsIdeas\FeatureFlags\Facades\Features;

class IndexLineTest extends TestCase
{
    use LazilyRefreshDatabase;

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
    public function index_line_shows_chore_information()
    {
        // Arrange
        // Create chore with known information
        $frequency = new Frequency(Frequency::DAILY, 3);
        $chore     = Chore::factory([
            'title'              => 'Clean the sink',
            'frequency_id'       => $frequency->id,
            'frequency_interval' => $frequency->interval,
        ])
            ->withFirstInstance()
            ->create();

        // Act
        // Create IndexLine
        $component = Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ]);

        // Assert
        // We see all information
        $component->assertSee($frequency->__toString());
        $component->assertSee('Clean the sink');
    }

    /** @test */
    public function index_line_has_assigned_user_image()
    {
        // Arrange
        // Create user with profile picture and chore assigned to them
        Features::turnOn('index-profile-photos');
        $user = $this->testUser([
            'profile_photo_path' => 'test_photo_url.jpg',
        ])['user'];
        $chore = Chore::factory()
            ->for($user)
            ->withFirstInstance()
            ->create();

        // Act
        // Create IndexLine
        $component = Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ]);

        // Assert
        // User profile picture is available
        $component->assertSeeHtml("src=\"$user->profile_photo_url\"");
    }

    /** @test */
    public function snooze_until_tomorrow_emits_event()
    {
        // Arrange
        // create chore with due date
        $this->testUser();
        $chore = Chore::factory()
            ->withFirstInstance()
            ->for($this->user)
            ->create();

        // Act
        // create indexline and snooze
        $component = Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('snoozeUntilTomorrow');

        // Assert
        // Event emitted
        $component->assertEmitted('chore_instance.updated');
    }

    /** @test */
    public function snooze_until_weekend_emits_event()
    {
        // Arrange
        // create chore with due date
        $this->testUser();
        $chore = Chore::factory()
            ->withFirstInstance()
            ->for($this->user)
            ->create();

        // Act
        // create indexline and snooze
        $component = Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('snoozeUntilWeekend');

        // Assert
        // Event emitted
        $component->assertEmitted('chore_instance.updated');
    }
}
