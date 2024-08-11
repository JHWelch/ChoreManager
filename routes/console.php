<?php

use App\Actions\Schedule\CountStreaks;
use App\Actions\Schedule\SendDailyDigest;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


// If Demo is enabled reseed database daily.
Schedule::command('migrate:fresh --force')
    ->daily()
    ->when(fn () => config('demo.enabled'))
    ->then(function () {
        $this->call('db:seed --force --class=DemoSeeder');
    });

Schedule::call(new SendDailyDigest)
    ->dailyAt('07:00');

Schedule::call(new CountStreaks)
    ->dailyAt('02:00');
