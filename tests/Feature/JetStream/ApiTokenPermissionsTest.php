<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);
uses(LazilyRefreshDatabase::class);

test('api token permissions can be updated', function () {
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
                ->set(['managingPermissionsFor' => $token])
                ->set(['updateApiTokenForm' => [
                    'permissions' => [
                        'delete',
                        'missing-permission',
                    ],
                ]])
                ->call('updateApiToken');

    expect($user->fresh()->tokens->first()->can('delete'))->toBeTrue();
    expect($user->fresh()->tokens->first()->can('read'))->toBeFalse();
    expect($user->fresh()->tokens->first()->can('missing-permission'))->toBeFalse();
});
