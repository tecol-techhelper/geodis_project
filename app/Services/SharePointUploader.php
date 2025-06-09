<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Services\AzureGraphService;

class SharePointUploader
{
    public function __construct(
        protected AzureGraphService $auth
    ) {}

    public function upload(string $absolutePath, string $remoteFileName): array
    {
        $token = $this->auth->getAccessToken();
        $siteId = config('services.msgraph.site_id');
        $folderPath = config('services.msgraph.folder_path');

        $stream = fopen($absolutePath, 'r');

        if (!$stream) {
            throw new \Exception("No se pudo abrir el archivo: $absolutePath");
        }

        // Codificar solo cada parte del path por separado
        $encodedPath = collect(explode('/', $folderPath))
            ->map(fn($segment) => rawurlencode($segment))
            ->implode('/');

        $encodedFileName = rawurlencode($remoteFileName);
        $fullPath = "{$encodedPath}/{$encodedFileName}";

        $url = "https://graph.microsoft.com/v1.0/sites/{$siteId}/drive/root:/{$fullPath}:/content";

        $response = Http::withToken($token)
            ->withBody(stream_get_contents($stream), 'application/octet-stream')
            ->put($url);

        fclose($stream);

        if (!$response->successful()) {
            throw new \Exception('Error al subir el archivo: ' . $response->body());
        }

        return $response->json();
    }
}
