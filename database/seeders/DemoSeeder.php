<?php

namespace Database\Seeders;

use App\Enums\Frequency;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DemoSeeder extends Seeder
{
    private function chores()
    {
        return [
            [
                'title'        => 'Do the dishes',
                'frequency_id' => Frequency::DAILY,
                'due_date'     => today(),
                'description'  => null,
            ],
            [
                'title'        => 'Take out the trash',
                'frequency_id' => Frequency::WEEKLY,
                'due_date'     => today()->addDays(5),
                'description'  => null,
            ],
            [
                'title'        => 'Get groceries',
                'frequency_id' => Frequency::WEEKLY,
                'due_date'     => today()->addDays(3),
                'description'  => <<<'EOT'
                ### Grocery List
                * Bread
                * Milk
                * Butter
                * Brocolli
                * Apples
                EOT,
            ],
            [
                'title'        => 'Polish boots',
                'frequency_id' => Frequency::QUARTERLY,
                'due_date'     => today()->addDays(45),
                'description'  => <<<'EOT'
                [How to shine boots](https://www.wikihow.com/Polish-Boots)
                EOT,
            ],
            [
                'title'        => 'Take out recycling',
                'frequency_id' => Frequency::WEEKLY,
                'due_date'     => today()->addDays(9),
            ],
        ];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name'              => 'Demo User',
            'email'             => 'demo@example.com',
            'email_verified_at' => now(),
            'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $team = Team::create([
            'name'          => 'Demo User\'s Team',
            'personal_team' => true,
            'user_id'       => $user->id,
        ]);

        collect($this->chores())->each(function ($chore) use ($user, $team) {
            $due_date = Arr::pull($chore, 'due_date');

            $chore = Chore::create(array_merge($chore, [
                'user_id' => $user->id,
                'team_id' => $team->id,
            ]));

            ChoreInstance::create([
                'chore_id' => $chore->id,
                'user_id'  => $user->id,
                'due_date' => $due_date,
            ]);
        });
    }
}
