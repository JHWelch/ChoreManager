<?php

namespace App\Mail;

use App\Models\ChoreInstance;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyDigest extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(protected User $user) {}

    public function build(): self
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

    /**
     * Map a chore instance to an array.
     *
     * @return array<string, string>
     */
    protected function mapChoreInstance(ChoreInstance $instance): array
    {
        return [
            'title' => $instance->chore->title,
            'url' => route('chores.show', ['chore' => $instance->chore]),
        ];
    }
}
