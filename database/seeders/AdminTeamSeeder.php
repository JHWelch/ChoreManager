<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Team::factory()->create([
            'name'          => 'Admins',
            'personal_team' => false,
            'user_id'       => User::first()?->id ?? User::factory(),
        ]);
    }
}
