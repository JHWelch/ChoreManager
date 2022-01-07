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
}
