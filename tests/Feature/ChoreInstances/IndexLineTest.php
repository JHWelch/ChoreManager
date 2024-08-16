<?php

use App\Enums\Frequency;
use App\Enums\FrequencyType;
use App\Livewire\ChoreInstances\IndexLine;
use App\Models\Chore;
use Livewire\Livewire;

it('can complete a chore instance', function () {
    $user = $this->testUser()['user'];
    $chore = Chore::factory()->for($user)->withFirstInstance()->create();
    $chore_instance = $chore->nextChoreInstance;

    Livewire::test(IndexLine::class, [
        'chore' => $chore,
    ])->call('complete');

    $chore_instance->refresh();
    expect($chore_instance->is_completed)->toBeTrue();
});

test('when a chore instance is completed a new one is created daily', function () {
    $now = today();
    $user = $this->testUser()['user'];
    $chore = Chore::factory()
        ->daily()
        ->for($user)
        ->withFirstInstance($now)
        ->create();

    Livewire::test(IndexLine::class, [
        'chore' => $chore,
    ])->call('complete');

    $chore->refresh();
    expect($chore->nextChoreInstance->due_date->toDateString())->toEqual($now->addDay()->toDateString());
});

test('index line shows chore information', function () {
    $frequency = new Frequency(FrequencyType::daily, 3);
    $chore = Chore::factory([
        'title' => 'Clean the sink',
        'frequency_id' => $frequency->frequencyType,
        'frequency_interval' => $frequency->interval,
    ])
        ->withFirstInstance()
        ->create();

    $component = Livewire::test(IndexLine::class, [
        'chore' => $chore,
    ]);

    $component->assertSee($frequency->__toString());
    $component->assertSee('Clean the sink');
});

test('index line has assigned user image', function () {
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
});

test('snooze until tomorrow emits event', function () {
    $this->testUser();
    $chore = Chore::factory()
        ->withFirstInstance()
        ->for($this->user)
        ->create();

    $component = Livewire::test(IndexLine::class, [
        'chore' => $chore,
    ])->call('snoozeUntilTomorrow');

    $component->assertDispatched('chore_instance.updated');
});

test('snooze until weekend emits event', function () {
    $this->testUser();
    $chore = Chore::factory()
        ->withFirstInstance()
        ->for($this->user)
        ->create();

    $component = Livewire::test(IndexLine::class, [
        'chore' => $chore,
    ])->call('snoozeUntilWeekend');

    $component->assertDispatched('chore_instance.updated');
});
