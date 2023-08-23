<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $fields = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $request->user()->deviceTokens()->create($fields);

        return response()->json(null, 201);
    }
}
