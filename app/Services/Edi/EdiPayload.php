<?php

namespace App\Services\Edi;

final class EdiPayload
{
    public function __construct(
        public readonly string $content,
        public readonly string $fileName,
        public readonly string $transmissionId,
        public readonly string $payloadHash,
        public readonly string $messageType,  // IFTSTA
        public readonly string $direction,    // OUT
    ) {}
}
