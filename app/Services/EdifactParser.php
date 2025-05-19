<?php

namespace App\Services;

use DateTime;
use Illuminate\Support\Facades\Log;

class EdifactParser
{
    // Mapping EDI fields with DB fields base on codes
    private const QUALIFIER_MAP = [
        '137' => 'recived_at', // Fecha del mensaje
        '11'  => 'despatch_date',          // Fecha de despacho (Pending confirmation by GEODIS)
        '132' => 'arrival_date',           // Fecha de llegada (Pending confirmation by GEODIS)
        '2'   => 'delivery_date',          // Fecha de entrega (Pending confirmation by GEODIS)
        // More mapping fields are Pending confirmation by GEODIS
    ];

    /**
     * Main methods: 
     * - Parsing segment general file
     * - Parsing every segment
     */
    public static function parse(string $content): array
    {
        $lines = preg_split("/'\\s*/", trim($content));
        $data = [];

        foreach ($lines as $line) {
            $line = trim($line);
            // If the current segment is empty, pass to the next segment
            if ($line === "") {
                continue;
            }
            // Extracting the name of the segment
            $fields = explode("+", $line);
            $segmentID = $fields[0];

            switch ($segmentID) {
                case 'UNB':
                    $data = array_merge($data, self::parseUNB($fields));
                    break;
                case 'UNH':
                    $data = array_merge($data, self::parseUNH($fields));
                    break;
                case 'BGM':
                    $data = array_merge($data, self::parseBGM($fields));
                    break;
                case 'DTM':
                    $data = array_merge($data, self::parseDTM($fields));
                    break;
            }
        }

        return $data;
    }

    //Parsing UNH segment
    //UNH+<MessageRefNumber>+<MessageIdentifier>:<Version>:<Release>:<Agency>'
    public static function parseUNH(array $parts): array
    {
        return [
            'message_id' => $parts[1] ?? null,
            'message_type' => isset($parts[2]) ? explode(':', $parts[2])[0] : null
        ];
    }

    //Parsing BGM segment
    //BGM+<DocumentCode>+<DocumentNumber>+<MessageFunctionCode>'
    public static function  parseBGM(array $parts): array
    {
        return [
            'document_code' => $parts[1] ?? null,
            'documento_number' => $parts[2] ?? null,
            'message_function_code' => $parts[3] ?? null
        ];
    }

    //Parsing DTM segment
    //DTM+<DateQualifier>:<DateValue>:<FormatCode>'
    public static function parseDTM(array $parts): array
    {
        // Validating if there's date values setted
        if (!isset($parts[1])) return [];

        // Separating every subfield
        $elements = explode(":", $parts[1]);

        $dateType = $elements[0];
        $date = $elements[1];
        $formatCode = $elements[2];


        if (!$dateType || !$date || !$formatCode) return [];


        // Formating date base on format code
        $dateFormated = self::interDateByFormat($date, $formatCode);

        if (!$dateFormated) return [];

        // Searching the database field name base on dateType code
        $dbField = self::QUALIFIER_MAP[$dateType] ?? null;

        return $dbField ? [$dbField => $dateFormated] : [];
    }

    /**
     * Auxiliar methods: 
     * - Formating dates
     */

    // Formating by format code
    public static function interDateByFormat(string $value, string $formatCode): ?string
    {
        return match ($formatCode) {
            '102' => self::formatingGeneralDate($value, 'Ymd'),
            '203' => self::formatingGeneralDate($value, 'YmdHi'),
            '303' => self::formatingGeneralDate($value, 'Hi'),
            '718' => self::formatingDateDDYYMM($value),
            default => null
        };
    }

    //Formating 102, 203, 303 and 718 codes
    public static function formatingGeneralDate(string $value, string $format): ?string
    {
        return \DateTime::createFromFormat($format, $value)?->format('Y-m-d H:i:s');
    }

    // Formating Date and Hour for YYMMDD
    public static function formatingDateDDYYMM(string $dateHour)
    {
        [$date, $hour] = explode(':', $dateHour);

        // Extracting Day, Month and Year
        $year = "20" . substr($date, 0, 2);
        $month = substr($date, 2, 2);
        $day = substr($date, 4, 2);

        // Extracting Hour and Minute
        $hourExt = substr($hour, 0, 2);
        $minuteExt = substr($hour, 2, 2);

        $dateTime = "{$year}-{$month}-{$day} {$hourExt}:{$minuteExt}";

        $date = \DateTime::createFromFormat('Y-m-d H:i', $dateTime);

        return $date?->format('Y-m-d H:i:s');
    }


    /**
     * Another methods for texting
     */

    public static function extractMessages(string $content): array
    {
        // Separating the message in segments
        $segments = preg_split("/'\\s*/", trim($content));
        $messages = [];
        $currentMessage = [];

        // walk through the array
        foreach ($segments as $segment) {
            // Discarting all spaces and empy segments
            $segment = trim($segment);
            if ($segment === '') continue;


            // Validating if the current segment starts with UNH or UNT
            if (str_starts_with($segment, 'UNH')) {
                $currentMessage = [$segment];
            } elseif (str_starts_with($segment, 'UNT')) {
                // Adding final segment (UNT) to the message block UNH - UNT
                $currentMessage[] = $segment;
                $messages[] = implode("'", $currentMessage) . "'";
                $currentMessage = [];
            } else {
                // Adding intermediate segments between UNH and UNT
                $currentMessage[] = $segment;
            }
        }

        // Returning a list of message(s)
        return $messages;
    }

    /**
     * Parsing UMBsegment apart because there's one per file
     */

    public static function extractUNBSegment(string $content): ?array
    {
        $segments = preg_split("/'\\s*/", trim($content));

        foreach ($segments as $segment) {
            if (str_starts_with($segment, 'UNB')) {
                $parts = explode('+', $segment);
                return self::parseUNB($parts);
            }
        }

        return null;
    }

    //Parsing UNB segment
    public static function parseUNB(array $parts): array
    {
        return [
            'message_date' => isset($parts[4]) ? self::formatingDateDDYYMM($parts[4]) : null,
            'transmission_id' => $parts[5] ?? null
        ];
    }
}
