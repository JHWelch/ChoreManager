<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyDigest extends Mailable
{
    use Queueable, SerializesModels;

    protected User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $chore_instances = $this->user
            ->choreInstances()
            ->notCompleted()
            ->dueToday()
            ->with('chore')
            ->get();

        return $this->view('mail.daily-digest', [
            'chore_instances' => $chore_instances->map(function ($instance) {
                return  [
                    'title' => $instance->chore->title,
                    'url'   => route('chores.show', ['chore' => $instance->chore]),
                ];
            }),
        ]);
    }
}
