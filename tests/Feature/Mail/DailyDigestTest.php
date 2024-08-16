<?php

use App\Mail\DailyDigest;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;

uses(\Illuminate\Foundation\Testing\WithFaker::class);

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('daily digest has users chores for the day', function () {
    $chores = Chore::factory()
        ->withFirstInstance(today(), $this->user->id)
        ->count(3)
        ->create();

    $mail_digest = new DailyDigest($this->user);

    foreach ($chores as $chore) {
        $mail_digest->assertSeeInHtml($chore->title);
    }
});

test('daily digest has users past due chores', function () {
    $chores = Chore::factory()
        ->has(ChoreInstance::factory()->for($this->user)->pastDue())
        ->count(3)
        ->create();

    $mail_digest = new DailyDigest($this->user);

    foreach ($chores as $chore) {
        $mail_digest->assertSeeInHtml($chore->title);
    }
});

test('daily digest does not show chores due in the future', function () {
    $chore = Chore::factory()
        ->withFirstInstance(today()->addDay(), $this->user->id)
        ->create();

    $mail_digest = new DailyDigest($this->user);

    $mail_digest->assertDontSeeInHtml($chore->title);
});

test('daily digest does not show chores assigned to different user', function () {
    $other_user = User::factory()->create();
    $chore = Chore::factory()
        ->withFirstInstance(today(), $other_user->id)
        ->create();

    $mail_digest = new DailyDigest($this->user);

    $mail_digest->assertDontSeeInHtml($chore->title);
});

test('daily digest does not show chores that are completed', function () {
    $chore = Chore::factory()->create();
    ChoreInstance::factory()
        ->dueToday()
        ->completed()
        ->for($chore)
        ->for($this->user)
        ->create();

    $mail_digest = new DailyDigest($this->user);

    $mail_digest->assertDontSeeInHtml($chore->title);
});

test('if user has no chores due today display message', function () {
    $mail_digest = new DailyDigest($this->user);

    $mail_digest->assertDontSeeInHtml('<ul>');
    $mail_digest->assertSeeInHtml('No chores due today!');
});

test('chores have links to web', function () {
    $chore = Chore::factory()
        ->withFirstInstance(today(), $this->user->id)
        ->create();

    $mail_digest = new DailyDigest($this->user);

    $chore_url = route('chores.show', ['chore' => $chore]);
    $mail_digest->assertSeeInHtml($chore_url);
});
