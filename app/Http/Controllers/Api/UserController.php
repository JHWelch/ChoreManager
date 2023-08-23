<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthUserResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    public function show(User $user): JsonResource
    {
        AuthUserResource::wrap('data');

        return Auth::user()->is($user)
            ? AuthUserResource::make($user)
            : UserResource::make($user);
    }
}
