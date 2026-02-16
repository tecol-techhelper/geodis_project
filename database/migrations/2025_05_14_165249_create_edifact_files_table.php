<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edifact_files', function (Blueprint $table) {
            $table->id();

            /**
             * Identificador de transmisión (siempre único).
             * Si GEODIS reutiliza/repiten IDs en el futuro, esto te explotará.
             * Por ahora lo dejamos como lo tenías: unique().
             */
            $table->string('transmission_id')->unique();

            /**
             * Tipo de mensaje (según tu regla: solo IFCSUM inbound, IFTSTA outbound).
             */
            $table->enum('message_type', ['IFTSTA', 'IFCSUM']);

            /**
             * Dirección explícita (para no depender de "si es IFCSUM entonces IN").
             */
            $table->enum('direction', ['IN', 'OUT'])->default('IN');

            /**
             * Estado del proceso: útil para control, UI y reintentos.
             * - IN: RECEIVED -> PROCESSED o FAILED
             * - OUT: PENDING -> SENT o FAILED
             */
            $table->enum('status', ['RECEIVED', 'PENDING', 'PROCESSED', 'SENT', 'FAILED'])->default('RECEIVED');

            /**
             * Nombre del archivo (tal cual se generó/recibió).
             */
            $table->string('file_name');

            /**
             * Referencia opcional para consulta rápida (no confíes en esto como "la verdad").
             */
            $table->string('purchase_order')->nullable();

            /**
             * Timestamps reales (DATETIME) para auditoría y depuración.
             */
            $table->dateTime('received_at')->nullable();
            $table->dateTime('sent_at')->nullable();

            /**
             * Rutas/URLs (local y remota).
             */
            $table->string('file_url', 2048)->nullable();
            $table->string('file_path', 2048)->nullable();     // ruta local (storage/app/...)
            $table->string('remote_disk', 50)->nullable();      // ej: "sftp_geodis"
            $table->string('remote_path', 2048)->nullable();    // ruta remota exacta en el SFTP
            $table->dateTime('uploaded_at')->nullable();        // cuándo se subió realmente al SFTP

            /**
             * Reintentos / errores.
             */
            $table->unsignedInteger('attempts')->default(0);
            $table->dateTime('last_attempt_at')->nullable();
            $table->text('error_message')->nullable();

            /**
             * Idempotencia: hash del payload (SHA-256) para evitar duplicados exactos.
             * Útil sobre todo en OUT.
             */
            $table->char('payload_hash', 64)->nullable();

            /**
             * Relación directa con el servicio.
             */
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();

            $table->softDeletes();
            $table->timestamps();

            /**
             * Índices útiles para consultas y listados.
             */
            $table->index(['service_id', 'message_type', 'direction'], 'idx_edifact_service_type_dir');
            $table->index(['status', 'direction'], 'idx_edifact_status_dir');
            $table->index('payload_hash', 'idx_edifact_payload_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edifact_files');
    }
};
