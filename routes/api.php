<?php

use App\Http\Controllers\Api\Geodis\ExpedienteController;
use App\Http\Middleware\EnsureGeodisApiHttps;
use App\Http\Middleware\EnsurePassportClientCredentialsAreAvailable;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\CheckToken;

$geodisMiddleware = [
    EnsureGeodisApiHttps::class,
    class_exists(CheckToken::class)
        ? 'client:expedientes.read'
        : EnsurePassportClientCredentialsAreAvailable::class,
];

Route::middleware($geodisMiddleware)->group(function () {
    Route::get('/expedientes', [ExpedienteController::class, 'index'])
        ->name('api.geodis.expedientes.index');

    Route::get('/geodis/ping', [ExpedienteController::class, 'ping'])
        ->name('api.geodis.ping');
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Endpoint no encontrado.',
    ], 404);
});
