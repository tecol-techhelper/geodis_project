<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SharePointUploader
{
    public function __construct(
        protected AzureGraphService $auth
    ) {}

    public function upload(string $absolutePath, string $remoteFileName): array
    {
        $token = $this->auth->getAccessToken();
        $siteId = config('services.msgraph.site_id');
        $folderPath = trim((string) config('services.msgraph.folder_path'), '/');
        $baseUrl = "https://graph.microsoft.com/v1.0/sites/{$siteId}/drive";
        $fullPath = ltrim("{$folderPath}/{$remoteFileName}", '/');
        $directory = dirname($fullPath);

        if ($directory !== '.') {
            $this->ensureDirectory($token, $baseUrl, $directory);
        }

        $encodedPath = $this->encodePath($fullPath);
        $url = "{$baseUrl}/root:/{$encodedPath}:/content";
        $stream = fopen($absolutePath, 'r');

        if (!$stream) {
            throw new \RuntimeException("No se pudo abrir el archivo: {$absolutePath}");
        }

        try {
            $response = Http::withToken($token)
                ->withBody(stream_get_contents($stream), 'application/octet-stream')
                ->put($url);
        } finally {
            fclose($stream);
        }

        if (!$response->successful()) {
            throw new \RuntimeException('Error al subir el archivo: ' . $response->body());
        }

        return $response->json();
    }

    public function delete(string $fileUrl): void
    {
        $token = $this->auth->getAccessToken();
        $siteId = config('services.msgraph.site_id');
        $baseUrl = "https://graph.microsoft.com/v1.0/sites/{$siteId}/drive";
        $root = Http::withToken($token)->get("{$baseUrl}/root", ['$select' => 'webUrl']);

        if (!$root->successful()) {
            throw new \RuntimeException('No se pudo consultar la biblioteca de SharePoint: ' . $root->body());
        }

        $rootUrl = parse_url((string) $root->json('webUrl'));
        $targetUrl = parse_url($fileUrl);
        $rootPath = rtrim(rawurldecode($rootUrl['path'] ?? ''), '/') . '/';
        $targetPath = rawurldecode($targetUrl['path'] ?? '');

        if (empty($rootUrl['host']) || empty($targetUrl['host'])
            || strtolower($rootUrl['host']) !== strtolower($targetUrl['host'])
            || ($targetUrl['scheme'] ?? '') !== 'https'
            || !str_starts_with($targetPath, $rootPath)) {
            throw new \RuntimeException('El enlace del soporte no pertenece a la biblioteca de SharePoint configurada.');
        }

        $relativePath = substr($targetPath, strlen($rootPath));
        $segments = explode('/', $relativePath);
        if (in_array('', $segments, true) || in_array('.', $segments, true) || in_array('..', $segments, true)) {
            throw new \RuntimeException('La ruta del soporte en SharePoint no es válida.');
        }

        $item = Http::withToken($token)->get("{$baseUrl}/root:/{$this->encodePath($relativePath)}", [
            '$select' => 'id,file',
        ]);

        // Permite retirar el registro si el archivo ya no existe en SharePoint.
        if ($item->status() === 404) {
            return;
        }

        if (!$item->successful() || !is_array($item->json('file')) || !$item->json('id')) {
            throw new \RuntimeException('No se pudo identificar el archivo de SharePoint: ' . $item->body());
        }

        $itemId = rawurlencode($item->json('id'));
        $response = Http::withToken($token)->delete("{$baseUrl}/items/{$itemId}");

        if (!$response->successful() && $response->status() !== 404) {
            throw new \RuntimeException('No se pudo eliminar el archivo de SharePoint: ' . $response->body());
        }
    }

    private function ensureDirectory(string $token, string $baseUrl, string $directory): void
    {
        $parentPath = '';

        foreach (explode('/', trim($directory, '/')) as $segment) {
            $path = $parentPath === '' ? $segment : "{$parentPath}/{$segment}";
            $folderUrl = "{$baseUrl}/root:/{$this->encodePath($path)}";
            $response = Http::withToken($token)->get($folderUrl);

            if ($response->status() === 404) {
                $childrenUrl = $parentPath === ''
                    ? "{$baseUrl}/root/children"
                    : "{$baseUrl}/root:/{$this->encodePath($parentPath)}:/children";
                $response = Http::withToken($token)->post($childrenUrl, [
                    'name' => $segment,
                    'folder' => (object) [],
                    '@microsoft.graph.conflictBehavior' => 'fail',
                ]);

                // Otra carga puede haber creado la misma carpeta entre la consulta y el POST.
                if ($response->status() === 409) {
                    $response = Http::withToken($token)->get($folderUrl);
                }
            }

            if (!$response->successful() || !is_array($response->json('folder'))) {
                throw new \RuntimeException("No se pudo preparar la carpeta de SharePoint {$path}: " . $response->body());
            }

            $parentPath = $path;
        }
    }

    private function encodePath(string $path): string
    {
        return implode('/', array_map('rawurlencode', explode('/', $path)));
    }
}
