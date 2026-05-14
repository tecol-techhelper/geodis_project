<?php

namespace App\Http\Controllers;

use App\Models\EdifactFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EdifactFileViewController extends Controller
{
    public function __invoke(Request $request, EdifactFile $edifactFile)
    {
        $fileName = $edifactFile->file_name ?? 'archivo.edi';
        $content = null;

        if (!empty($edifactFile->file_path) && File::exists($edifactFile->file_path)) {
            $content = File::get($edifactFile->file_path);
        } elseif (!empty($edifactFile->file_url) && Str::startsWith($edifactFile->file_url, ['http://', 'https://'])) {
            $response = Http::timeout(15)->get($edifactFile->file_url);
            if ($response->successful()) {
                $content = $response->body();
            }
        } elseif (!empty($edifactFile->file_url) && Storage::disk('public')->exists($edifactFile->file_url)) {
            $content = Storage::disk('public')->get($edifactFile->file_url);
        } elseif (!empty($edifactFile->file_url) && Storage::disk('local')->exists($edifactFile->file_url)) {
            $content = Storage::disk('local')->get($edifactFile->file_url);
        }

        if ($content === null) {
            abort(404, 'El archivo no está disponible en el sitio');
        }

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
