<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureGeodisApiHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->shouldProtectRequest($request) || ! $this->shouldEnforceHttps()) {
            return $next($request);
        }

        if ($request->isSecure()) {
            return $next($request);
        }

        return new JsonResponse([
            'message' => 'Las solicitudes a esta API deben realizarse sobre HTTPS.',
        ], 400);
    }

    private function shouldProtectRequest(Request $request): bool
    {
        return $request->is('api/*') || $request->is('oauth/token');
    }

    private function shouldEnforceHttps(): bool
    {
        $configured = config('geodis_api.enforce_https');

        if ($configured !== null) {
            return filter_var($configured, FILTER_VALIDATE_BOOL);
        }

        if (app()->environment('local', 'testing')) {
            return false;
        }

        return true;
    }
}
