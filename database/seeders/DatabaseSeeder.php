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
        User::factory()
            ->has(Team::factory())
            ->create([
                'name'     => 'John Smith',
                'email'    => 'jsmith@example.com',
                'password' => Hash::make('pw1234'),
            ]);
        $this->call(ChoreSeeder::class);
    }
}
