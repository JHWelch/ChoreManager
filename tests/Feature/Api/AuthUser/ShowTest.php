<?php

test('a user can view their own info', function () {
    $this->user();

    $response = $this->get(route('api.auth_user.show'));

    $response->assertOk();
    $response->assertJson(['user' => [
        'id' => $this->user->id,
        'name' => $this->user->name,
        'email' => $this->user->email,
        'profile_photo_path' => $this->user->profile_photo_path,
        'current_team_id' => $this->user->current_team_id,
    ],
    ]);
});
