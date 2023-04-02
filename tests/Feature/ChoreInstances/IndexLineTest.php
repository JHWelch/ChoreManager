<?php

namespace Tests\Feature\ChoreInstances;

use App\Enums\Frequency;
use App\Enums\FrequencyType;
use App\Http\Livewire\ChoreInstances\IndexLine;
use App\Models\Chore;
use Livewire\Livewire;
use Tests\TestCase;

class IndexLineTest extends TestCase
{
    /** @test */
    public function can_complete_a_chore_instance(): void
    {
        $user           = $this->testUser()['user'];
        $chore          = Chore::factory()->for($user)->withFirstInstance()->create();
        $chore_instance = $chore->nextChoreInstance;

        Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('complete');

        $chore_instance->refresh();
        $this->assertTrue($chore_instance->is_completed);
    }

    /** @test */
    public function when_a_chore_instance_is_completed_a_new_one_is_created_daily(): void
    {
        $now   = today();
        $user  = $this->testUser()['user'];
        $chore = Chore::factory()
            ->daily()
            ->for($user)
            ->withFirstInstance($now)
            ->create();

        Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('complete');

        $chore->refresh();
        $this->assertEquals(
            $now->addDay()->toDateString(),
            $chore->nextChoreInstance->due_date->toDateString(),
        );
    }

    /** @test */
    public function index_line_shows_chore_information(): void
    {
        $frequency = new Frequency(FrequencyType::daily, 3);
        $chore     = Chore::factory([
            'title'              => 'Clean the sink',
            'frequency_id'       => $frequency->frequencyType,
            'frequency_interval' => $frequency->interval,
        ])
            ->withFirstInstance()
            ->create();

        $component = Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ]);

        $component->assertSee($frequency->__toString());
        $component->assertSee('Clean the sink');
    }

    /** @test */
    public function index_line_has_assigned_user_image(): void
    {
        $this->markTestSkipped('Feature disabled.');
        $user = $this->testUser([
            'profile_photo_path' => 'test_photo_url.jpg',
        ])['user'];
        $chore = Chore::factory()
            ->for($user)
            ->withFirstInstance()
            ->create();

        $component = Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ]);

        $component->assertSeeHtml("src=\"$user->profile_photo_url\"");
    }

    /** @test */
    public function snooze_until_tomorrow_emits_event(): void
    {
        $this->testUser();
        $chore = Chore::factory()
            ->withFirstInstance()
            ->for($this->user)
            ->create();

        $component = Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('snoozeUntilTomorrow');

        $component->assertEmitted('chore_instance.updated');
    }

    /** @test */
    public function snooze_until_weekend_emits_event(): void
    {
        $this->testUser();
        $chore = Chore::factory()
            ->withFirstInstance()
            ->for($this->user)
            ->create();

        $component = Livewire::test(IndexLine::class, [
            'chore' => $chore,
        ])->call('snoozeUntilWeekend');

        $component->assertEmitted('chore_instance.updated');
    }
}
