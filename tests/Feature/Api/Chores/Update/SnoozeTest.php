<?php

use App\Models\Chore;
use Illuminate\Support\Carbon;

beforeEach(function () {
    $this->snoozeEndpoint = fn (Chore $chore, Carbon $nextDueDate) => $this->patch(
        route('api.chores.update', ['chore' => $chore]),
        ['next_due_date' => $nextDueDate->toDateString()],
    );
});

test('user can snooze chore', function () {
    $this->user();
    $chore = Chore::factory()
        ->for($this->user)
        ->withFirstInstance()
        ->create();
    $date = Carbon::now()->addDays(3);

    $response = ($this->snoozeEndpoint)($chore, $date);

    $response->assertOk();
    expect($chore->refresh()->next_due_date->toDateString())->toEqual($date->toDateString());
});
