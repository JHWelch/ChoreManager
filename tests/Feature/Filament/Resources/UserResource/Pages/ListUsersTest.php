<?php

namespace Tests\Feature\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    /** @test */
    public function admin_can_see_index_page(): void
    {
        $this->adminTestUser();

        $response = $this->get(UserResource::getUrl('index'));

        $response->assertSuccessful();
    }

    /** @test */
    public function standard_user_cannot_see_index_page(): void
    {
        $this->testUser();

        $response = $this->get(UserResource::getUrl('index'));

        $response->assertForbidden();
    }

    /** @test */
    public function can_see_user_fields(): void
    {
        $this->adminTestUser();
        $user = User::factory()->create();

        Livewire::test(ListUsers::class)
            ->assertSee($user->name)
            ->assertSee($user->created_at->format(config('tables.date_time_format')))
            ->assertSee($user->updated_at->format(config('tables.date_time_format')))
            ->assertSee($user->email);
    }
}
