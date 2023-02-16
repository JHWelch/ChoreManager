<?php

namespace Database\Seeders;

use App\Enums\Frequency;
use App\Models\Chore;
use App\Models\ChoreInstance;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
                'title'              => 'Take out recycling',
                'frequency_id'       => Frequency::WEEKLY,
                'frequency_interval' => 2,
                'due_date'           => today()->addDays(9),
            ],
            [
                'title'        => 'Renew Car Registration',
                'frequency_id' => Frequency::YEARLY,
                'due_date'     => today()->addDays(125),
            ],
        ];
    }

    private function other_chores()
    {
        return [
            [
                'title'        => 'Cook Dinner',
                'frequency_id' => Frequency::DAILY,
                'due_date'     => today(),
                'description'  => null,
            ],
            [
                'title'              => 'Vaccuum living room',
                'frequency_id'       => Frequency::WEEKLY,
                'frequency_interval' => 2,
                'due_date'           => today()->addDays(5),
                'description'        => null,
            ],
            [
                'title'        => 'Bike maintenance',
                'frequency_id' => Frequency::QUARTERLY,
                'due_date'     => today()->addDays(45),
            ],
            [
                'title'        => 'Pick up dry cleaning',
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
    public function run(): void
    {
        $user = User::create([
            'name'              => 'Demo User',
            'email'             => 'demo@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make(Str::random()),
        ]);

        $team = Team::create([
            'name'          => 'Demo User\'s Team',
            'personal_team' => true,
            'user_id'       => $user->id,
        ]);

        $second_user = User::create([
            'name'              => 'Steve Smith',
            'email'             => 'ssmith@example.com',
            'email_verified_at' => now(),
            'password'          => Hash::make(Str::random()),
        ]);

        $second_user->teams()->attach($team, ['role' => 'editor']);

        collect([
            [
                'user'   => $user,
                'chores' => $this->chores(),
            ],
            [
                'user'   => $second_user,
                'chores' => $this->other_chores(),
            ],
        ])->each(function ($item) use ($team) {
            $user = Arr::get($item, 'user');
            collect(Arr::get($item, 'chores'))->each(function ($chore) use ($user, $team) {
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
        });
    }
}
