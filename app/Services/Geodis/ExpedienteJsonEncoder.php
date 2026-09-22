<?php

namespace App\Services\Geodis;

use UnexpectedValueException;

/** Encodes the API's stored decimals as JSON numbers without a float conversion. */
final class ExpedienteJsonEncoder
{
    private const DECIMAL_FIELDS = ['cantidad', 'valor_unitario', 'valor_total'];

    public function encode(array $payload): string
    {
        return $this->encodeValue($payload);
    }

    private function encodeValue(mixed $value, ?string $field = null): string
    {
        if ($value !== null && in_array($field, self::DECIMAL_FIELDS, true)) {
            // Only validated decimal literals may bypass json_encode's string quoting.
            if ((! is_string($value) && ! is_int($value))
                || ! preg_match('/\A-?(?:0|[1-9][0-9]*)(?:\.[0-9]+)?\z/', (string) $value)) {
                throw new UnexpectedValueException("Invalid stored decimal for {$field}.");
            }

            $decimal = (string) $value;

            return preg_replace('/\.0+$/', '', $decimal) ?? $decimal;
        }

        if (! is_array($value)) {
            return json_encode($value, JSON_THROW_ON_ERROR);
        }

        $isList = array_is_list($value);
        $parts = [];
        foreach ($value as $key => $item) {
            $encoded = $this->encodeValue($item, is_string($key) ? $key : null);
            $parts[] = $isList ? $encoded : json_encode((string) $key, JSON_THROW_ON_ERROR).':'.$encoded;
        }

        return ($isList ? '[' : '{').implode(',', $parts).($isList ? ']' : '}');
    }
}
