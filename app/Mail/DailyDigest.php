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
        $chore_instance_groups = $this->user
            ->choreInstances()
            ->notCompleted()
            ->dueTodayOrPast()
            ->orderBy('due_date')
            ->with('chore')
            ->get()
            ->mapToGroups(function ($chore_instance) {
                if ($chore_instance->due_date->startOfDay() < today()) {
                    return ['past_due' => $this->mapChoreInstance($chore_instance)];
                }

                return ['today' => $this->mapChoreInstance($chore_instance)];
            });

        return $this->markdown('mail.daily-digest', [
            'chore_instance_groups' => $chore_instance_groups,
        ]);
    }

    protected function mapChoreInstance($instance)
    {
        return [
            'title' => $instance->chore->title,
            'url'   => route('chores.show', ['chore' => $instance->chore]),
        ];
    }
}
