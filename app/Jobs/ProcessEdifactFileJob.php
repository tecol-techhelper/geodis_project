<?php

namespace App\Jobs;

use App\Models\EdifactFile;
use App\Models\Notification;
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

            // Extraer UNB una vez
            $unbData = EdifactParser::extractUNBSegment($content);
            if (!$unbData || !isset($unbData['transmission_id'])) {
                Log::warning("Archivo sin UNB válido: {$this->fileName}");
                return;
            }

            // Validar si existe transmission_id en la base de datos
            if (EdifactFile::where('transmission_id', $unbData['transmission_id'])->exists()) {
                Log::info("Transmisión ya procesada: {$unbData['transmission_id']} ({$this->fileName})");
                return;
            }

            // Guardando los datos de la transmision en la tabla edifact_files
            $dataDB = $this->mapEdifactFile($unbData, $this->fileName, $this->tipoMensaje);
            EdifactFile::create($dataDB);
            Log::info("Transmisión registrada en edifact_files: {$this->fileName}");

            // Procesar cada bloque UNH–UNT por separado
            $messages = EdifactParser::extractMessages($content);
            if (empty($messages)) {
                Log::warning("Archivo sin bloques UNH–UNT: {$this->fileName}");
                return;
            }

            foreach ($messages as $message) {
                $data = EdifactParser::parse($message);
                $combined = array_merge($unbData, $data);

                if (!isset($combined['transmission_id'], $combined['message_type'])) {
                    Log::warning("Mensaje {$message} ignorado por datos incompletos en archivo {$this->fileName}");
                    continue;
                }

                Notification::create([
                    'title' => 'Notificación de servicio',
                    'message' => "Se procesó la orden de servicio {$combined['documento_number']} desde el archivo {$this->fileName}.",
                    'purchase_order' => $combined['documento_number'] ?? 'Desconocida',
                    'is_read' => false,
                ]);

                // $dataDB = $this->mapEdifactFile($combined, $this->fileName, $this->tipoMensaje);
                // EdifactFile::create($dataDB);

                // Log::info("Mensaje {$message} procesado correctamente en archivo {$this->fileName}");
            }
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
