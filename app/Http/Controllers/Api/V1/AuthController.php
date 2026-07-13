<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\ApiToken;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:100'],
        ]);

        $user = User::query()
            ->where('username', $credentials['username'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Username atau password tidak valid.'],
            ]);
        }

        $plainToken = Str::random(80);
        $expiresAt = now()->addDays(30);

        ApiToken::create([
            'user_id' => $user->id,
            'name' => $credentials['device_name'] ?? 'mobile',
            'token' => hash('sha256', $plainToken),
            'abilities' => ['*'],
            'expires_at' => $expiresAt,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token_type' => 'Bearer',
                'access_token' => $plainToken,
                'expires_at' => $expiresAt,
                'user' => new UserResource($user),
            ],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new UserResource($request->user()),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->attributes->get('api_token')?->forceFill([
            'revoked_at' => now(),
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil.',
        ]);
    }
}
