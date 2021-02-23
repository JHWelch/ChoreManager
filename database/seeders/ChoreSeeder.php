<?php

namespace Database\Seeders;

use App\Models\Chore;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::all()->each(fn ($user) => Chore::factory()->for($user)->count(5)->create());
    }
}
