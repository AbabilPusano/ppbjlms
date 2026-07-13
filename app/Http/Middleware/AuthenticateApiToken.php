<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $plainToken = $request->bearerToken();

        if (! $plainToken) {
            return $this->unauthorized('Token API belum dikirim.');
        }

        $apiToken = ApiToken::query()
            ->with('user')
            ->where('token', hash('sha256', $plainToken))
            ->first();

        if (! $apiToken?->isValid()) {
            return $this->unauthorized('Token API tidak valid atau sudah kedaluwarsa.');
        }

        $apiToken->forceFill(['last_used_at' => now()])->save();

        $request->attributes->set('api_token', $apiToken);
        $request->setUserResolver(fn () => $apiToken->user);

        return $next($request);
    }

    private function unauthorized(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 401);
    }
}
