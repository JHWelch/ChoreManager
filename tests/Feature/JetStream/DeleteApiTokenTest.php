<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;
use Tests\TestCase;


test('api tokens can be deleted', function () {
    if (! Features::hasApiFeatures()) {
        return $this->markTestSkipped('API support is not enabled.');
    }

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $token = $user->tokens()->create([
        'name'      => 'Test Token',
        'token'     => Str::random(40),
        'abilities' => ['create', 'read'],
    ]);

    Livewire::test(ApiTokenManager::class)
                ->set(['apiTokenIdBeingDeleted' => $token->id])
                ->call('deleteApiToken');

    expect($user->fresh()->tokens)->toHaveCount(0);
});
