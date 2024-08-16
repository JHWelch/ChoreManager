<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\LogoutOtherBrowserSessionsForm;

use function Pest\Livewire\livewire;

test('other browser sessions can be logged out', function () {
    $this->actingAs(User::factory()->create());

    livewire(LogoutOtherBrowserSessionsForm::class)
        ->set('password', 'password')
        ->call('logoutOtherBrowserSessions');
});
