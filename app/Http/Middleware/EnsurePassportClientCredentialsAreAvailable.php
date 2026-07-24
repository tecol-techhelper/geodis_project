<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePassportClientCredentialsAreAvailable
{
    public function handle(Request $request, Closure $next): Response
    {
        return new JsonResponse([
            'message' => 'La autenticación OAuth2 de GEODIS no está disponible en este entorno.',
        ], 503);
    }
}
