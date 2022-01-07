<?php

namespace Tests\Feature\Mail;

use App\Mail\DailyDigest;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DailyDigestTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function daily_digest_has_users_chores_for_the_day()
    {
        // Arrange
        // Create user with Chores
        $user   = User::factory()->create();
        $chores = Chore::factory()
            ->withFirstInstance(today(), $user->id)
            ->count(3)
            ->create();

        // Act
        // create new daily digest
        $mail_digest = new DailyDigest($user);

        // Assert
        // Has chore titles
        foreach ($chores as $chore) {
            $mail_digest->assertSeeInHtml($chore->title);
        }
    }

    /** @test */
    public function daily_digest_does_not_show_chores_due_different_day()
    {
        // Arrange
        // Create user with chore not due today
        $user  = User::factory()->create();
        $chore = Chore::factory()
            ->withFirstInstance(today()->addDay(), $user->id)
            ->create();

        // Act
        // create new daily digest
        $mail_digest = new DailyDigest($user);

        // Assert
        // Has chore title
        $mail_digest->assertDontSeeInHtml($chore->title);
    }

    /** @test */
    public function daily_digest_does_not_show_chores_assigned_to_different_user()
    {
        // Arrange
        // Create user and chore for a different user
        $user        = User::factory()->create();
        $other_user  = User::factory()->create();
        $chore       = Chore::factory()
            ->withFirstInstance(today(), $other_user->id)
            ->create();

        // Act
        // create new daily digest
        $mail_digest = new DailyDigest($user);

        // Assert
        // Has chore title
        $mail_digest->assertDontSeeInHtml($chore->title);
    }

    /** @test */
    public function daily_digest_does_not_show_chores_that_are_completed()
    {
        // Arrange
        // Create user and chore already completed
        $user        = User::factory()->create();
        $chore       = Chore::factory()->create();
        ChoreInstance::factory()
            ->dueToday()
            ->completed()
            ->for($chore)
            ->for($user)
            ->create();

        // Act
        // create new daily digest
        $mail_digest = new DailyDigest($user);

        // Assert
        // Has chore title
        $mail_digest->assertDontSeeInHtml($chore->title);
    }
}
