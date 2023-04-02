<?php

namespace Tests\Feature\Resources\Views;

use Tests\TestCase;

class NavigationMenuTest extends TestCase
{
    /** @test */
    public function user_can_see_account_management_links(): void
    {
        $this->testUser();

        $this->view('navigation-menu')
            ->assertSee('Manage Account')
            ->assertSee('Profile')
            ->assertSee('API Tokens')
            ->assertSee('iCalendar Links');
    }

    /** @test */
    public function admin_users_can_see_admin_actions(): void
    {
        $this->adminTestUser();

        $this->view('navigation-menu')
            ->assertSee('Admin Features')
            ->assertSee('Admin Dashboard');
    }

    /** @test */
    public function non_admin_users_cannot_see_admin_actions(): void
    {
        $this->testUser();

        $this->view('navigation-menu')
            ->assertDontSee('Admin Features')
            ->assertDontSee('Admin Dashboard');
    }
}
