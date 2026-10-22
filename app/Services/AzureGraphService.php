<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class AzureGraphService
{
    public function getAccessToken(): string
    {
        $tenantId = config('services.msgraph.tenant_id');
        $clientId = config('services.msgraph.client_id');
        $clientSecret = config('services.msgraph.client_secret');

        $response = Http::asForm()->post("https://login.microsoftonline.com/{$tenantId}/oauth2/v2.0/token", [
            'grant_type' => 'client_credentials',
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'scope' => 'https://graph.microsoft.com/.default',
        ]);

        if (! $response->successful()) {
            throw new RuntimeException('No se pudo autenticar con Azure (HTTP '.$response->status().').');
        }

        $token = $response->json('access_token');
        if (! is_string($token) || trim($token) === '') {
            throw new RuntimeException('Azure no devolvió un token de acceso válido.');
        }

        return $token;
    }
}
