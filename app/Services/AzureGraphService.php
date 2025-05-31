<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

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

        if (!$response->successful()) {
            // Mostramos todo el contenido del error en texto plano
            dd($response->status(), $response->json());
        }

        return $response->json()['access_token'];
    }
}
