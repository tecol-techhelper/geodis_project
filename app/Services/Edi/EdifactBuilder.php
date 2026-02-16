<?php

namespace App\Services\Edi;

final class EdifactBuilder
{
    private array $segments = [];

    public function __construct(
        private readonly string $segTerm = "'",
        private readonly string $dataSep = "+",
        private readonly string $compSep = ":",
    ) {}

    /**
     * Agrega un segmento EDIFACT.
     *
     * Ej:
     *  seg('UNH', ['1', ['IFTSTA','D','96A','UN']])
     *  -> UNH+1+IFTSTA:D:96A:UN'
     */
    public function seg(string $tag, array $elements = []): self
    {
        $parts = [$tag];

        foreach ($elements as $el) {
            if (is_array($el)) {
                // componente compuesto (A:B:C)
                $parts[] = $this->join($el, $this->compSep);
            } else {
                $parts[] = $this->safe($el);
            }
        }

        $this->segments[] = $this->join($parts, $this->dataSep) . $this->segTerm;

        return $this;
    }

    public function countSegments(): int
    {
        return count($this->segments);
    }

    public function toString(): string
    {
        // EDIFACT suele ser 1 segmento por línea (más legible)
        return implode("\n", $this->segments) . "\n";
    }

    private function join(array $items, string $sep): string
    {
        $out = [];
        foreach ($items as $it) {
            $out[] = $this->safe($it);
        }
        // Ojo: EDIFACT permite vacíos, pero no queremos meter null literal.
        return implode($sep, $out);
    }

    private function safe(mixed $value): string
    {
        if ($value === null) return '';
        $v = (string) $value;

        // NOTA: si tu contenido puede traer separadores, hay que “escaparlos”
        // con release char (?) según UNB. Por ahora, limpio básico:
        $v = str_replace(["\r", "\n"], ' ', $v);

        return trim($v);
    }
}
