<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class GeodisApiAudit
{
    public function handle(Request $request, Closure $next): Response
    {
        $startedAt = microtime(true);
        $response = $next($request);

        if (config('geodis_api.audit_log', true)) {
            Log::info('GEODIS API request', [
                'route' => $request->route()?->getName() ?? $request->path(),
                'method' => $request->method(),
                'status' => $response->getStatusCode(),
                'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
            ]);
        }

        return $response;
    }
}
