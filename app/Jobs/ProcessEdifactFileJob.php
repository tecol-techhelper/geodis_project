<?php

namespace App\Jobs;

use App\Models\EdifactFile;
use App\Services\EdifactParser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProcessEdifactFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public string $fullpath,
        public string $fileName,
        public string $tipoMensaje,
        public string $relativePath
    ) {}

    public function handle(): void
    {
        try {
            if (!File::exists($this->fullpath)) {
                throw new \Exception("File not found at path {$this->fullpath}");
            }

            $content = File::get($this->fullpath);
            $data = EdifactParser::parse($content);

            if (!isset($data['transmission_id'], $data['message_type'])) {
                Log::warning("Archivo ignorado por datos incompletos: {$this->fileName}");
                return;
            }

            if (EdifactFile::where('transmission_id', $data['transmission_id'])->exists()) {
                Log::info("Transmisión ya procesada: {$data['transmission_id']} ({$this->fileName})");
                return;
            }

            $dataDB = $this->mapEdifactFile($data, $this->fileName, $this->tipoMensaje);
            EdifactFile::create($dataDB);

            Log::info("Archivo procesado correctamente: {$this->fileName}");

        } catch (\Throwable $e) {
            Log::error("Error al procesar el archivo {$this->fileName}: " . $e->getMessage());
        }
    }

    private function mapEdifactFile(array $datos, string $fileName, string $tipo): array
    {
        return [
            'transmission_id' => $datos['transmission_id'],
            'message_type'    => $tipo,
            'file_name'       => $fileName,
            'file_path'        => $this->fullpath, //Full path
            'file_url'        => $this->relativePath, //Relative path starting in storage/
            'purchase_order'  => $datos['documento_number'] ?? null,
            'recived_at'      => $datos['recived_at'] ?? null,
            
        ];
    }
}
