<?php

namespace App\Actions\Schedule;

use App\Mail\DailyDigest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendDailyDigest
{
    public function __invoke(): void
    {
        $users = User::withSetting('has_daily_digest', true);

        foreach ($users as $user) {
            $this->sendToUser($user);
        }
    }

    protected function sendToUser(User $user): void
    {
        Mail::to($user)->send(new DailyDigest($user));
    }
}
