<?php

namespace App\Http\Controllers;

use App\Models\EdifactFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EdifactFileDownloadController extends Controller
{
    public function __invoke(Request $request, EdifactFile $edifactFile)
    {
        $fileName = $edifactFile->file_name ?? 'archivo.edi';

        // 1) Si hay file_path absoluto y existe, descargarlo
        if (!empty($edifactFile->file_path) && File::exists($edifactFile->file_path)) {
            return response()->download($edifactFile->file_path, $fileName);
        }

        // 2) Si file_url es URL pública, redirigir
        if (!empty($edifactFile->file_url) && Str::startsWith($edifactFile->file_url, ['http://', 'https://'])) {
            return redirect()->away($edifactFile->file_url);
        }

        // 3) Probar en disco public
        if (!empty($edifactFile->file_url) && Storage::disk('public')->exists($edifactFile->file_url)) {
            return Storage::disk('public')->download($edifactFile->file_url, $fileName);
        }

        // 4) Probar en disco local (private)
        if (!empty($edifactFile->file_url) && Storage::disk('local')->exists($edifactFile->file_url)) {
            return Storage::disk('local')->download($edifactFile->file_url, $fileName);
        }

        abort(404, 'El archivo no está disponible en el sitio');
    }
}
