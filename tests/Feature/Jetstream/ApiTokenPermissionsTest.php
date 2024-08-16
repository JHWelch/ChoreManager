<?php

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;

use function Pest\Livewire\livewire;

test('api token permissions can be updated', function () {
    if (! Features::hasApiFeatures()) {
        $this->markTestSkipped('API support is not enabled.');
    }

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $token = $user->tokens()->create([
        'name' => 'Test Token',
        'token' => Str::random(40),
        'abilities' => ['create', 'read'],
    ]);

    livewire(ApiTokenManager::class)
        ->set(['managingPermissionsFor' => $token])
        ->set(['updateApiTokenForm' => [
            'permissions' => [
                'delete',
                'missing-permission',
            ],
        ]])
        ->call('updateApiToken');

    expect($user->fresh()->tokens->first())
        ->can('delete')->toBeTrue()
        ->can('read')->toBeFalse()
        ->can('missing-permission')->toBeFalse();
});
