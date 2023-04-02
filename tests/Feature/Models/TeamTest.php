<?php

namespace Tests\Feature\Models;

use App\Models\Team;
use Database\Seeders\AdminTeamSeeder;
use Tests\TestCase;

class TeamTest extends TestCase
{
    /** @test */
    public function adminTeam_returns_null_if_team_not_seeded(): void
    {
        $admin_team = Team::adminTeam();

        $this->assertNull($admin_team);
    }

    /** @test */
    public function adminTeam_returns_team_named_Admins(): void
    {
        $this->seed(AdminTeamSeeder::class);

        $admin_team = Team::adminTeam();

        $this->assertEquals('Admins', $admin_team->name);
    }

    /** @test */
    public function adminTeam_cached_for_subsequent_calls(): void
    {
        $this->seed(AdminTeamSeeder::class);

        $admin_team = Team::adminTeam();

        $this->assertNotNull($admin_team);

        Team::where('name', 'Admins')->delete();

        $admin_team = Team::adminTeam();

        $this->assertNotNull($admin_team);
    }
}
