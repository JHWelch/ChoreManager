<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if (! $request->expectsJson()) {
            if (config('demo.enabled', false)) {
                Auth::login(User::where('email', 'demo@example.com')->first());

                return $request->getRequestUri();
            } else {
                return route('login');
            }
        }
    }
}
