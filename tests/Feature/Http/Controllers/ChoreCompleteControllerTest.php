<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Chore;
use Tests\TestCase;

class ChoreCompleteControllerTest extends TestCase
{
    /** @test */
    public function chore_complete_redirects_user_to_chore_show_with_flag(): void
    {
        $this->testUser();
        $chore    = Chore::factory()->create();
        $response = $this->get(route('chores.complete.index', ['chore' => $chore]));

        $response
            ->assertSessionHas('complete', true)
            ->assertRedirect(route('chores.show', ['chore' => $chore]));
    }
}
