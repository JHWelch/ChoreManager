<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (config('demo.enabled')) {
            $this->call(DemoSeeder::class);

            return;
        }

        User::factory()
            ->has(
                Team::factory()
                    ->state(function (array $attributes, User $user) {
                        return ['user_id' => $user->id];
                    })
                    ->hasAttached(User::factory()->count(10), ['role' => 'editor']),
                'ownedTeams'
            )
            ->create([
                'name'     => 'John Smith',
                'email'    => 'jsmith@example.com',
                'password' => Hash::make('pw1234'),
            ])
                ->switchTeam(Team::first());

        $this->call(ChoreSeeder::class);
    }
}
