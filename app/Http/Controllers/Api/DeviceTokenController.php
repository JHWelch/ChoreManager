<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $fields = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $existingToken = DeviceToken::where('token', $fields['token'])->first();

        if ($existingToken) {
            $existingToken->touch();

            return response()->json();
        }

        $request->user()->deviceTokens()->create($fields);

        return response()->json(status: 201);
    }
}
