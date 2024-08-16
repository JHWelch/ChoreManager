<?php

use App\Models\User;

test('new user automatically has default settings', function () {
    $user = User::factory()->create();

    expect(false)->toEqual($user->settings->has_daily_digest);
});
