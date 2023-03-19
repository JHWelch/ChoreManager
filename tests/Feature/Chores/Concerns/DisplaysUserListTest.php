<?php

namespace Tests\Feature\Chores\Concerns;

use App\Http\Livewire\Chores\Concerns\DisplaysUserList;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class DisplaysUserListTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function has_list_of_users_with_id_name_and_profile_photo(): void
    {
        $this->testUser(['name' => 'Alfred Albertson']);
        $users = User::factory()
            ->sequence(
                ['name' => 'Bertie Bertsch'],
                ['name' => 'Cecil Cesar'],
            )
            ->hasAttached($this->team)
            ->count(2)
            ->create();

        $class = new ClassWithTrait();
        $class->mountDisplaysUserList();

        $this->assertEquals([
            ['id' => 1, 'name' => 'Alfred Albertson', 'profile_photo_url' => $this->user->profile_photo_url],
            ['id' => 2, 'name' => 'Bertie Bertsch', 'profile_photo_url' => $users[0]->profile_photo_url],
            ['id' => 3, 'name' => 'Cecil Cesar', 'profile_photo_url' => $users[1]->profile_photo_url],
        ], $class->user_options);
    }
}

class ClassWithTrait
{
    use DisplaysUserList;
}
