<?php

test('user can see account management links', function () {
    $this->testUser();

    $this->view('navigation-menu')
        ->assertSee('Manage Account')
        ->assertSee('Profile')
        ->assertSee('API Tokens')
        ->assertSee('iCalendar Links');
});

test('admin users can see admin actions', function () {
    $this->adminTestUser();

    $this->view('navigation-menu')
        ->assertSee('Admin Features')
        ->assertSee('Admin Dashboard');
});

test('non admin users cannot see admin actions', function () {
    $this->testUser();

    $this->view('navigation-menu')
        ->assertDontSee('Admin Features')
        ->assertDontSee('Admin Dashboard');
});
