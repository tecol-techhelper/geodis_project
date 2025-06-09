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
            if ($line === "") continue;

            $fields = explode("+", $line);
            $segmentID = $fields[0];

            try {
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
                    case 'NAD':
                        $data = array_merge($data, self::parseNAD($fields) ?? []);
                        break;
                    case 'LOC':
                        $data = array_merge($data, self::parseLOC($fields) ?? []);
                        break;
                    case 'CNI':
                        $data = array_merge($data, self::parseCNI($fields));
                        break;
                    case 'CNT':
                        $data = array_merge($data, self::parseCNT($fields) ?? []);
                        break;
                    case 'TDT':
                        $data = array_merge($data, self::parseTDT($fields) ?? []);
                        break;
                    case 'STS':
                        $data = array_merge($data, self::parseSTS($fields));
                        break;
                    case 'EQD':
                        $data = array_merge($data, self::parseEQD($fields) ?? []);
                        break;
                    case 'FTX':
                        $data = array_merge($data, self::parseFTX($fields) ?? []);
                        break;
                    case 'GID':
                        $data = array_merge($data, self::parseGID($fields) ?? []);
                        break;
                    case 'PIA':
                        $data = array_merge($data, self::parsePIA($fields) ?? []);
                        break;
                    case 'PCI':
                        $data = array_merge($data, self::parsePCI($fields) ?? []);
                        break;
                    case 'MEA':
                        $data = array_merge($data, self::parseMEA($fields) ?? []);
                        break;
                    case 'DIM':
                        $data = array_merge($data, self::parseDIM($fields) ?? []);
                        break;
                }
            } catch (\Throwable $e) {
                Log::error("[{$segmentID}] Error parseando segmento: " . $e->getMessage());
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
     * Pendientes implementar
     * Parsing NAD
     * NAD+<PartyQualifier>+<PartyID>+<CodeQualifier>+<Name>+<Street>+<City>+<Region>+<PostalCode>+<CountryCode>'
     */
    public static function parseNAD(array $parts): ?array
    {
        $qualifier = $parts[1] ?? null;
        if (!$qualifier) return null;

        return [
            'party_qualifier' => $qualifier,
            'party_id' => $parts[2] ?? null,
            'name' => $parts[4] ?? null,
            'street' => $parts[5] ?? null,
            'city' => $parts[6] ?? null,
            'region' => $parts[7] ?? null,
            'country' => $parts[9] ?? null,
        ];
    }

    // Parsing LOC
    // LOC+<PlaceQualifier>+<LocationIdentification>:<CodeQualifier>'
    public static function parseLOC(array $parts): ?array
    {
        $qualifier = $parts[1] ?? null;
        if (!$qualifier) return null;

        return [
            'place_qualifier' => $qualifier,
            'location_code' => $parts[2]
        ];
    }

    // Parsing CNI
    // CNI+<ConsignmentSequenceNumber>+<ConsignmentReferenceNumber>'
    public static function parseCNI(array $parts): array
    {
        return [
            'consignment_reference_number' => $parts[2] ?? null
        ];
    }

    // Parsing CNT
    // CNT+<ControlQualifier>:<Value>:<MeasurementUnit>'
    public static function parseCNT(array $parts): ?array
    {
        if (!isset($parts[1])) return null;

        $elements = explode(":", $parts[1]);

        $qualifier = $elements[0] ?? null;
        $value = $elements[1] ?? null;
        $measurementUnit = $elements[2] ?? null;

        if (!$qualifier) return null;

        return [
            'control_qualifier' => $qualifier,
            'value' => $value,
            'meausrementUnit' => $measurementUnit
        ];
    }

    // Parsing STS
    // STS+<StatusEventSequence>+<StatusCode>'
    public static function parseSTS(array $parts): array
    {
        return [
            'status_code' => $parts[2] ?? null
        ];
    }

    // Parsing TDT
    // TDT+<TransportStageCode>+<TransportID>+<TransportMode>+<CarrierID>+<CarrierCode>:<CodeListQualifier>:<ResponsibleAgency>:<CarrierName>+<TransportMeansCode>+<NationalityCode>'
    public static function parseTDT(array $parts): ?array
    {
        $qualifier = $parts[1] ?? null;
        if (!$qualifier) return null;

        return [
            'transport_stage_code' => $qualifier,
            'transport_id' => $parts[2] ?? null,
            'transport_mode' => $parts[3] ?? null,
            'carrier_id' => $parts[4] ?? null,
            'carrier_details' => $parts[5] ?? null
        ];
    }

    // Parsing EQD
    // EQD+<EquipmentTypeCode>+<EquipmentID>+<FullOrEmptyIndicator>+<EquipmentSizeAndType>+<EquipmentSupplier>+<SealID>+<EquipmentStatus>+<OwnershipIndicator>'
    public static function parseEQD(array $parts): ?array
    {
        $equipmentType = $parts[1] ?? null;
        if (!$equipmentType) return null;

        return [
            'equipment_type' => $equipmentType,
            'equipment_id' => $parts[2] ?? null,
            'container_load_status' => $parts[3] ?? null
        ];
    }

    // Parsing GID
    // GID+<ItemNumber>+<PackageCount>:<PackageType>'
    public static function parseGID(array $parts): ?array
    {
        $itemNumber = $parts[1] ?? null;
        if (!$itemNumber) return null;

        $elements = explode(':', $parts[2]);
        $pkCount = $elements[0] ?? null;
        $pkType = $elements[1] ?? null;

        return [
            'item_number' => $itemNumber,
            'package_count' => $pkCount,
            'package_type' => $pkType
        ];
    }

    // Parsing PCI
    // PCI+<MarkingInstructions>+<PackageID>'
    public static function parsePCI(array $parts): ?array
    {
        $markingExistence = $parts[1] ?? null;
        if (!$markingExistence || $markingExistence == "25") return null;

        return [
            'marking_existence' => $markingExistence,
            'package_id' => $parts[2] ?? null
        ];
    }

    // Parsing PIA
    // PIA+<ProductIdFunctionCode>+<ProductId>:<CodeListQualifier>'
    public static function parsePIA(array $parts): ?array
    {
        if (!isset($parts[1])) return null;

        $idFunction = $parts[1] ?? null;
        $productID = $parts[2] ?? null;

        return [
            'id_function' => $idFunction,
            'product_id' => $productID
        ];
    }

    // Parsing MEA
    //MEA+<MeasurementPurpose>+<MeasurementAttribute>+<UnitCode>:<Value>'
    public static function parseMEA(array $parts): ?array
    {
        $purpose = $parts[1] ?? null;
        if (!isset($purpose)) return null;

        $measurementType = $parts[2] ?? null;

        $measurementData = explode(':', $parts[3]);

        $unit = $measurementData[0] ?? null;
        $value = $measurementData[1] ?? null;

        $hash = md5($purpose . '|' . $measurementType . '|' . $unit);

        return [
            'purpose' => $purpose,
            'measurement_type' => $measurementType,
            'unit' => $unit,
            'value' => $value,
            'hash' => $hash
        ];
    }

    // Parsing DIM
    // DIM+<DimensionQualifier>+<UnitCode>:<Length>:< Width >:<Height>'
    public static function parseDIM(array $parts): ?array
    {
        $qualifier = $parts[1] ?? null;
        if (!isset($qualifier)) return null;



        $dimData = explode(':', $parts[2]);

        $unit = $dimData[0] ?? null;
        $length = $dimData[1] ?? null;
        $width = $dimData[2] ?? null;
        $height = $dimData[3] ?? null;


        $hash = md5(implode('|', [$unit, $length, $width, $height]));

        return [
            'qualifier' => $qualifier,
            'unit' => $unit,
            'length' => $length,
            'width' => $width,
            'height' => $height,
            'hash' => $hash
        ];
    }

    // Parsing FTX
    // FTX+<TextSubjectQualifier>+<TextFunction>+<TextReference>+<TextLine1>:<TextLine2>:<TextLine3>'
    public static function parseFTX(array $parts): ?array
    {
        $qualifier = $parts[1] ?? null;
        $message = $parts[4] ?? null;

        if (!$qualifier || !$message) return null;

        return [
            'qualifier' => $parts[1],
            'message' => str_replace(':', "\n", $message)
        ];
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
    // UNB+<SyntaxIdentifier>:<SyntaxVersion>+<SenderID>:<SenderQualifier>+<ReceiverID>:<ReceiverQualifier>+<Date>:<Time>+<InterchangeControlReference>'
    public static function parseUNB(array $parts): array
    {
        return [
            'message_date' => isset($parts[4]) ? self::formatingDateDDYYMM($parts[4]) : null,
            'sender' => explode(':', $parts[2])[0] ?? null,
            'transmission_id' => $parts[5] ?? null
        ];
    }
}
