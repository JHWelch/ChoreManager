<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthUserResource;
use Illuminate\Support\Facades\Auth;

class AuthUserController extends Controller
{
    public function show() : AuthUserResource
    {
        return AuthUserResource::make(Auth::user());
    }
}
