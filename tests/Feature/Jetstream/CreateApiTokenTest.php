<?php

use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;

use function Pest\Livewire\livewire;

test('api tokens can be created', function () {
    if (! Features::hasApiFeatures()) {
        $this->markTestSkipped('API support is not enabled.');
    }

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    livewire(ApiTokenManager::class)
        ->set(['createApiTokenForm' => [
            'name' => 'Test Token',
            'permissions' => [
                'read',
                'update',
            ],
        ]])
        ->call('createApiToken');

    expect($user->fresh()->tokens)->toHaveCount(1);
    expect($user->fresh()->tokens->first()->name)->toEqual('Test Token');
    expect($user->fresh()->tokens->first()->can('read'))->toBeTrue();
    expect($user->fresh()->tokens->first()->can('delete'))->toBeFalse();
});
