<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthUserResource;
use Illuminate\Support\Facades\Auth;

class AuthUserController extends Controller
{
    /**
     * Return the current auth'd user.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return AuthUserResource::make(Auth::user());
    }
}
