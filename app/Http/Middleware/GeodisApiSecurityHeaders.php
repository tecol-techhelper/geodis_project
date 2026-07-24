<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GeodisApiSecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->shouldProtectRequest($request)) {
            return $next($request);
        }

        $response = $next($request);
        $response->headers->set('Cache-Control', 'no-store');
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000');
        }

        return $response;
    }

    private function shouldProtectRequest(Request $request): bool
    {
        return $request->is('api/*') || $request->is('oauth/token');
    }
}
