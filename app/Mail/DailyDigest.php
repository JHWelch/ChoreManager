<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
            'chore_instances' => $chore_instances->map(fn ($instance) => $instance->chore->title),
        ]);
    }
}
