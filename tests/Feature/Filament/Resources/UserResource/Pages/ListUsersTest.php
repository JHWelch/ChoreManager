<?php

namespace Tests\Feature\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListUsersTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function admin_can_see_index_page()
    {
        $this->testAdminUser();

        $response = $this->get(UserResource::getUrl('index'));

        $response->assertSuccessful();
    }

    /** @test */
    public function standard_user_cannot_see_index_page()
    {
        $this->testUser();

        $response = $this->get(UserResource::getUrl('index'));

        $response->assertForbidden();
    }

    /** @test */
    public function can_see_user_fields()
    {
        $this->testAdminUser();
        $user = User::factory()->create();

        Livewire::test(ListUsers::class)
            ->assertSee($user->name)
            ->assertSee($user->created_at->format(config('tables.date_time_format')))
            ->assertSee($user->updated_at->format(config('tables.date_time_format')))
            ->assertSee($user->email);
    }
}
