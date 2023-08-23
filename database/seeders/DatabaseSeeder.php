<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (config('demo.enabled')) {
            $this->call(DemoSeeder::class);

            return;
        }

        $this->call(AdminTeamSeeder::class);

        $user = User::factory()
            ->has(
                Team::factory()
                    ->state(function (array $attributes, User $user) {
                        return ['user_id' => $user->id];
                    })
                    ->hasAttached(User::factory()->count(10), ['role' => 'editor']),
                relationship: 'ownedTeams'
            )
            ->create([
                'name' => 'John Smith',
                'email' => 'jsmith@example.com',
                'password' => bcrypt('password'),
            ]);

        $user->switchTeam(Team::firstWhere('user_id', $user->id));
        $user->teams()->attach($this->adminTeam());

        $this->call(ChoreSeeder::class);
    }

    protected function adminTeam(): Team
    {
        if (is_null($team = Team::adminTeam())) {
            throw new \Exception('Admin team not found');
        }

        return $team;
    }
}
