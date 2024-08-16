<?php

use App\Models\User;
use App\Providers\AppServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\Features;

test('email verification screen can be rendered', function () {
    if (! Features::enabled(Features::emailVerification())) {
        $this->markTestSkipped('Email verification not enabled.');
    }

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->get('/email/verify');

    $response->assertOk();
});

test('email can be verified', function () {
    if (! Features::enabled(Features::emailVerification())) {
        $this->markTestSkipped('Email verification not enabled.');
    }

    Event::fake();

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1($user->email)]
    );

    $response = $this->actingAs($user)->get($verificationUrl);

    Event::assertDispatched(Verified::class);

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();
    $response->assertRedirect(AppServiceProvider::HOME . '?verified=1');
});

test('email can not verified with invalid hash', function () {
    if (! Features::enabled(Features::emailVerification())) {
        $this->markTestSkipped('Email verification not enabled.');
    }

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $user->id, 'hash' => sha1('wrong-email')]
    );

    $this->actingAs($user)->get($verificationUrl);

    expect($user->fresh()->hasVerifiedEmail())->toBeFalse();
});
