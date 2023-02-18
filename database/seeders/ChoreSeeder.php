<?php

namespace Database\Seeders;

use App\Models\Chore;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::with('ownedTeams', 'teams')
            ->get()
            ->each(fn ($user) => Chore::factory([
                'user_id' => $user->id,
                'team_id' => $user->allTeams()->first(),
            ])
                ->count(5)
                ->withFirstInstance(null, $user->id)
                ->create());
    }
}
