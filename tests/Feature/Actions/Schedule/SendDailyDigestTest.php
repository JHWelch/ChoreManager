<?php

namespace Tests\Feature\Actions\Schedule;

use App\Actions\Schedule\SendDailyDigest;
use App\Mail\DailyDigest;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendDailyDigestTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function user_with_digest_enabled_will_recieve_chore_email()
    {
        $user = User::factory()->create();
        $user->settings->update(['has_daily_digest' => true]);

        (new SendDailyDigest)();

        Mail::assertSent(DailyDigest::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /** @test */
    public function user_without_digest_enabled_will_not_recieve_chore_email()
    {
        $user = User::factory()->create();
        $user->settings->update(['has_daily_digest' => false]);

        (new SendDailyDigest)();

        Mail::assertNotSent(DailyDigest::class);
    }
}
