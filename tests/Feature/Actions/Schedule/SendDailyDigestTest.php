<?php

use App\Actions\Schedule\SendDailyDigest;
use App\Mail\DailyDigest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    Mail::fake();
});

test('user with digest enabled will recieve chore email', function () {
    $user = User::factory()->create();
    $user->settings->update(['has_daily_digest' => true]);

    (new SendDailyDigest)();

    Mail::assertSent(DailyDigest::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });
});

test('user without digest enabled will not recieve chore email', function () {
    $user = User::factory()->create();
    $user->settings->update(['has_daily_digest' => false]);

    (new SendDailyDigest)();

    Mail::assertNotSent(DailyDigest::class);
});
