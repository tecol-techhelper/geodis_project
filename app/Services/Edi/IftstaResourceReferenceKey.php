<?php

namespace App\Services\Edi;

use InvalidArgumentException;

class IftstaResourceReferenceKey
{
    private const PART_LENGTH = 3;

    private const PART_SPACE = 46656; // 36^3

    /**
     * Construye una clave de seis caracteres a partir del recurso y el estado.
     *
     * Los primeros tres caracteres resumen el identificador externo del recurso;
     * los últimos tres representan el identificador del estado en base 36.
     */
    public function make(string $resourceId, int $statusId): string
    {
        $resourceId = strtoupper(trim($resourceId));

        if ($resourceId === '') {
            throw new InvalidArgumentException('El identificador del recurso es obligatorio para construir RFF+FS.');
        }

        if ($statusId <= 0 || $statusId >= self::PART_SPACE) {
            throw new InvalidArgumentException('El identificador del estado no puede representarse en tres caracteres Base36.');
        }

        $resourceHash = hexdec(substr(hash('sha256', "IFTSTA-FS|{$resourceId}"), 0, 8));
        $resourcePart = $this->toBase36($resourceHash % self::PART_SPACE);
        $statusPart = $this->toBase36($statusId);

        return $resourcePart . $statusPart;
    }

    private function toBase36(int $value): string
    {
        return str_pad(
            strtoupper(base_convert((string) $value, 10, 36)),
            self::PART_LENGTH,
            '0',
            STR_PAD_LEFT,
        );
    }
}
