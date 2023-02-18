<?php

namespace Tests\Feature\Mail;

use App\Mail\DailyDigest;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DailyDigestTest extends TestCase
{
    use LazilyRefreshDatabase;
    use WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /** @test */
    public function daily_digest_has_users_chores_for_the_day(): void
    {
        $chores = Chore::factory()
            ->withFirstInstance(today(), $this->user->id)
            ->count(3)
            ->create();

        $mail_digest = new DailyDigest($this->user);

        foreach ($chores as $chore) {
            $mail_digest->assertSeeInHtml($chore->title);
        }
    }

    /** @test */
    public function daily_digest_has_users_past_due_chores(): void
    {
        $chores = Chore::factory()
            ->has(ChoreInstance::factory()->for($this->user)->pastDue())
            ->count(3)
            ->create();

        $mail_digest = new DailyDigest($this->user);

        foreach ($chores as $chore) {
            $mail_digest->assertSeeInHtml($chore->title);
        }
    }

    /** @test */
    public function daily_digest_does_not_show_chores_due_in_the_future(): void
    {
        $chore = Chore::factory()
            ->withFirstInstance(today()->addDay(), $this->user->id)
            ->create();

        $mail_digest = new DailyDigest($this->user);

        $mail_digest->assertDontSeeInHtml($chore->title);
    }

    /** @test */
    public function daily_digest_does_not_show_chores_assigned_to_different_user(): void
    {
        $other_user  = User::factory()->create();
        $chore       = Chore::factory()
            ->withFirstInstance(today(), $other_user->id)
            ->create();

        $mail_digest = new DailyDigest($this->user);

        $mail_digest->assertDontSeeInHtml($chore->title);
    }

    /** @test */
    public function daily_digest_does_not_show_chores_that_are_completed(): void
    {
        $chore = Chore::factory()->create();
        ChoreInstance::factory()
            ->dueToday()
            ->completed()
            ->for($chore)
            ->for($this->user)
            ->create();

        $mail_digest = new DailyDigest($this->user);

        $mail_digest->assertDontSeeInHtml($chore->title);
    }

    /** @test */
    public function if_user_has_no_chores_due_today_display_message(): void
    {
        $mail_digest = new DailyDigest($this->user);

        $mail_digest->assertDontSeeInHtml('<ul>');
        $mail_digest->assertSeeInHtml('No chores due today!');
    }

    /** @test */
    public function chores_have_links_to_web(): void
    {
        $chore = Chore::factory()
            ->withFirstInstance(today(), $this->user->id)
            ->create();

        $mail_digest = new DailyDigest($this->user);

        $chore_url = route('chores.show', ['chore' => $chore]);
        $mail_digest->assertSeeInHtml($chore_url);
    }
}
