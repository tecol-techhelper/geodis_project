<?php

use App\Http\Middleware\BlockedIpMiddleware;
use App\Http\Middleware\CheckUserIsActive;
use App\Http\Middleware\EnsureGeodisApiHttps;
use App\Http\Middleware\GeodisApiSecurityHeaders;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Http\Middleware\CheckToken;
use Laravel\Passport\Http\Middleware\CheckTokenForAnyScope;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(env('GEODIS_TRUSTED_PROXIES') ?: null);
        $middleware->append(GeodisApiSecurityHeaders::class);
        $middleware->append(EnsureGeodisApiHttps::class);

        $aliases = [
            'role' => RoleMiddleware::class,
            'blocked' => BlockedIpMiddleware::class,
            'is_active' => CheckUserIsActive::class,
        ];

        if (class_exists(CheckToken::class)) {
            $aliases['client'] = CheckToken::class;
            $aliases['scope'] = CheckTokenForAnyScope::class;
            $aliases['scopes'] = CheckToken::class;
        }

        $middleware->alias($aliases);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $isApiRequest = static fn (Request $request): bool => $request->is('api/*');

        $json = static fn (string $message, int $status, array $extra = []): JsonResponse => response()->json(
            array_merge(['message' => $message], $extra),
            $status
        );

        $exceptions->render(function (ValidationException $exception, Request $request) use ($isApiRequest, $json) {
            if (! $isApiRequest($request)) {
                return null;
            }

            return $json('Parámetros inválidos.', 422, [
                'errors' => $exception->errors(),
            ]);
        });

        $exceptions->render(function (AuthenticationException $exception, Request $request) use ($isApiRequest, $json) {
            if (! $isApiRequest($request)) {
                return null;
            }

            $message = $request->bearerToken()
                ? 'Token inválido o vencido.'
                : 'Token no proporcionado.';

            return $json($message, 401);
        });

        $exceptions->render(function (AuthorizationException $exception, Request $request) use ($isApiRequest, $json) {
            if (! $isApiRequest($request)) {
                return null;
            }

            return $json('No tiene permisos para consultar este recurso.', 403);
        });

        $exceptions->render(function (NotFoundHttpException $exception, Request $request) use ($isApiRequest, $json) {
            if (! $isApiRequest($request)) {
                return null;
            }

            return $json('Endpoint no encontrado.', 404);
        });

        $exceptions->render(function (MethodNotAllowedHttpException $exception, Request $request) use ($isApiRequest, $json) {
            if (! $isApiRequest($request)) {
                return null;
            }

            return $json('Método HTTP no permitido.', 405);
        });

        $exceptions->render(function (\Throwable $exception, Request $request) use ($isApiRequest, $json) {
            if (! $isApiRequest($request)) {
                return null;
            }

            if (is_a($exception, 'League\\OAuth2\\Server\\Exception\\OAuthServerException')) {
                $status = method_exists($exception, 'getHttpStatusCode')
                    ? $exception->getHttpStatusCode()
                    : 401;

                return match ($status) {
                    401 => $json(
                        $request->bearerToken() ? 'Token inválido o vencido.' : 'Token no proporcionado.',
                        401
                    ),
                    403 => $json('No tiene permisos para consultar este recurso.', 403),
                    400 => $json('Solicitud inválida.', 400),
                    default => $json('Error interno controlado.', $status >= 400 && $status < 500 ? $status : 500),
                };
            }

            if ($exception instanceof HttpExceptionInterface) {
                $status = $exception->getStatusCode();
                $message = match ($status) {
                    400 => 'Solicitud inválida.',
                    401 => $request->bearerToken() ? 'Token inválido o vencido.' : 'Token no proporcionado.',
                    403 => 'No tiene permisos para consultar este recurso.',
                    404 => 'Endpoint no encontrado.',
                    405 => 'Método HTTP no permitido.',
                    default => $status >= 500 ? 'Error interno controlado.' : ($exception->getMessage() ?: Response::$statusTexts[$status] ?? 'Error de API.'),
                };

                return $json($message, $status);
            }

            return $json('Error interno controlado.', 500);
        });
    })->create();
