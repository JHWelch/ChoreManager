<?php

namespace Tests\Feature\Api\AuthUser;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function a_user_can_view_their_own_info()
    {
        $this->testUser();

        $response = $this->get(route('api.auth_user.show'));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['user' => [
                'id'                 => $this->user->id,
                'name'               => $this->user->name,
                'email'              => $this->user->email,
                'profile_photo_path' => $this->user->profile_photo_path,
                'current_team_id'    => $this->user->current_team_id,
            ],
        ]);
    }
}