<?php

namespace App\Console;

use App\Actions\Schedule\CountStreaks;
use App\Actions\Schedule\SendDailyDigest;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // If Demo is enabled reseed database daily.
        $schedule
            ->command('migrate:fresh --force')
            ->daily()
            ->when(fn () => config('demo.enabled'))
            ->then(function () {
                ray('seeding');
                $this->call('db:seed --force --class=DemoSeeder');
            });

        $schedule
            ->call(new SendDailyDigest)
            ->dailyAt('07:00');

        $schedule
            ->call(new CountStreaks)
            ->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
