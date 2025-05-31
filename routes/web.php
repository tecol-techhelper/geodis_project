<?php

use App\Services\AzureGraphService;
use App\Services\SharePointUploader;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

// Route::get('/ver-token', function (AzureGraphService $azure) {
//     return $azure->getAccessToken();
// });
// Route::get('/get-site-id', function (AzureGraphService $azure) {
//     $token = $azure->getAccessToken();

//     // Reemplaza con tu dominio real y nombre del sitio en SharePoint
//     $domain = 'transtecolsas.sharepoint.com';
//     $siteName = 'CARGASECA'; // por ejemplo: 'proyectos'

//     $url = "https://graph.microsoft.com/v1.0/sites/{$domain}:/sites/{$siteName}";

//     $response = Http::withToken($token)->get($url);

//     return $response->json();
// });

Route::get('/subir-archivo', function (SharePointUploader $uploader) {
    $archivoLocal = 'documentos/prueba.pdf'; // Ubicado en storage/app/documentos
    $nombreFinal = 'archivo-subido.pdf';

    return $uploader->upload($archivoLocal, $nombreFinal);
});