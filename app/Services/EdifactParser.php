<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class EdifactParser
{
    // DTM qualifier -> campo “semántico”
    private const DTM_QUALIFIER_MAP = [
        '137' => 'recived_at',     // tu BD lo tiene así (mal escrito)
        '11'  => 'despatch_date',
        '132' => 'arrival_date',
        '2'   => 'delivery_date',
    ];

    /**
     * Parser principal orientado a DB.
     * Devuelve un payload con entidades alineadas a tus tablas.
     */
    public static function parseForDatabase(string $content, ?string $fileName = null): array
    {
        $segments = self::splitSegments($content);

        $unb = null;
        $unh = null;

        // Entidades DB
        $service = null;                // services (BGM)
        $serviceDates = [];             // service_dates (DTM)
        $serviceEquipment = [];         // service_equipment (EQD)

        $purchaseOrders = [];           // purchase_orders (CNI)
        $currentPOIndex = null;

        $currentItemIndex = null;

        // Metadata file-level para edifact_files
        $file = [
            'transmission_id' => null,
            'message_type' => null,     // UNH msg type
            'file_name' => $fileName,
            'purchase_order' => null,   // se decide: CNI o BGM
            'recived_at' => null,       // DATE
            'sended_at' => null,        // DATE (UNB)
        ];

        foreach ($segments as $seg) {
            $parts = explode('+', $seg);
            $tag = $parts[0] ?? null;
            if (!$tag) continue;

            try {
                switch ($tag) {

                    // -----------------
                    // UNB (file metadata)
                    // -----------------
                    case 'UNB': {
                            $unb = self::parseUNB($parts, $seg);
                            $file['transmission_id'] = $unb['transmission_id'];
                            $file['sended_at'] = $unb['sended_at'];
                            break;
                        }

                        // -----------------
                        // UNH (message type)
                        // -----------------
                    case 'UNH': {
                            $unh = self::parseUNH($parts, $seg);
                            $file['message_type'] = $unh['message_type'];
                            break;
                        }

                        // -----------------
                        // BGM -> SERVICES
                        // -----------------
                    case 'BGM': {
                            $bgm = self::parseBGM($parts, $seg);

                            // services requiere raw_segment NOT NULL
                            $service = [
                                'segment_tag' => 'BGM',
                                'raw_segment' => $bgm['raw_segment'],

                                // extras útiles para tu dominio (si luego los quieres mapear)
                                'document_code' => $bgm['document_code'],
                                'document_number' => $bgm['document_number'],
                                'message_function_code' => $bgm['message_function_code'],
                            ];

                            // Purchase order "candidate": BGM doc number (fallback)
                            if (!$file['purchase_order'] && !empty($bgm['document_number'])) {
                                $file['purchase_order'] = $bgm['document_number'];
                            }

                            break;
                        }

                        // -----------------
                        // DTM -> SERVICE_DATES (y también edifact_files.recived_at si aplica)
                        // -----------------
                    case 'DTM': {
                            $dtm = self::parseDTM($parts, $seg);
                            if (!$dtm) break;

                            // Si hay CNI activo, muchos DTM son por orden. Pero tu tabla service_dates
                            // solo cuelga de service_id, así que los guardamos como service_dates siempre.
                            $serviceDates[] = [
                                'segment_tag' => 'DTM',
                                'raw_segment' => $dtm['raw_segment'],
                                'service_date' => $dtm['date'],          // date Y-m-d (NOT NULL)
                                'format_date' => $dtm['format_code'],    // int (nullable)
                                'date_type_code' => $dtm['qualifier'],   // para lookup -> date_type_id
                            ];

                            // Si qualifier 137, eso lo quieres en edifact_files.recived_at
                            if (($dtm['qualifier'] ?? null) === '137' && !$file['recived_at']) {
                                $file['recived_at'] = $dtm['date'];
                            }

                            break;
                        }

                        // -----------------
                        // EQD -> SERVICE_EQUIPMENT
                        // -----------------
                    case 'EQD': {
                            $eqd = self::parseEQD($parts, $seg);
                            if (!$eqd) break;

                            $serviceEquipment[] = [
                                'segment_tag' => 'EQD',
                                'raw_segment' => $eqd['raw_segment'],
                                'equipment_identifier' => $eqd['equipment_identifier'],
                                'equipment_type_code' => $eqd['equipment_type_code'], // lookup -> equipment_type_id
                            ];

                            break;
                        }

                        // -----------------
                        // CNI -> PURCHASE_ORDERS (abre orden)
                        // -----------------
                    case 'CNI': {
                            $cni = self::parseCNI($parts, $seg);

                            $purchaseOrders[] = [
                                'segment_tag' => 'CNI',
                                'raw_segment' => $cni['raw_segment'],
                                'purchase_order_secuence' => $cni['sequence'] ? (int)$cni['sequence'] : null,
                                'purchase_order_number' => $cni['consignment_reference_number'],

                                // hijos
                                'items' => [],
                                'references' => [],
                                'contacts' => [],
                                'measurements' => [],
                                'requirements' => [],
                            ];

                            $currentPOIndex = count($purchaseOrders) - 1;
                            $currentItemIndex = null;

                            // Para edifact_files.purchase_order: si CNI trae número, tiene prioridad
                            if (!empty($cni['consignment_reference_number'])) {
                                $file['purchase_order'] = $cni['consignment_reference_number'];
                            }

                            break;
                        }

                        // -----------------
                        // GID -> PURCHASE_ORDER_ITEMS (hijo de la orden)
                        // -----------------
                    case 'GID': {
                            $gid = self::parseGID($parts, $seg);
                            if (!$gid) break;

                            // Si llega GID sin CNI previo: crea una orden "dummy" (para no perder el ítem)
                            if ($currentPOIndex === null) {
                                $purchaseOrders[] = [
                                    'segment_tag' => 'CNI',
                                    'raw_segment' => null,
                                    'purchase_order_secuence' => null,
                                    'purchase_order_number' => $file['purchase_order'], // lo que haya
                                    'items' => [],
                                    'references' => [],
                                    'contacts' => [],
                                    'measurements' => [],
                                    'requirements' => [],
                                ];
                                $currentPOIndex = count($purchaseOrders) - 1;
                            }

                            $purchaseOrders[$currentPOIndex]['items'][] = [
                                'segment_tag' => 'GID',
                                'raw_segment' => $gid['raw_segment'],
                                'item_number' => (int)$gid['item_number'],     // NOT NULL
                                'item_count' => (int)$gid['item_count'],       // NOT NULL
                                'item_type' => (string)$gid['item_type'],      // NOT NULL

                                // futuros hijos si luego creas tablas: PIA/MEA/DIM/PCI...
                                'products' => [],
                                'packages' => [],
                                'measurements' => [],
                                'dimensions' => [],
                            ];

                            $currentItemIndex = count($purchaseOrders[$currentPOIndex]['items']) - 1;
                            break;
                        }

                        // -----------------
                        // RFF -> ORDER_REFERENCES (cuelga de purchase_order_id)
                        // -----------------
                    case 'RFF': {
                            $rff = self::parseRFF($parts, $seg);
                            if (!$rff) break;

                            if ($currentPOIndex === null) {
                                // Sin orden, lo dejamos “huérfano” en la primera PO dummy
                                $purchaseOrders[] = [
                                    'segment_tag' => 'CNI',
                                    'raw_segment' => null,
                                    'purchase_order_secuence' => null,
                                    'purchase_order_number' => $file['purchase_order'],
                                    'items' => [],
                                    'references' => [],
                                    'contacts' => [],
                                    'measurements' => [],
                                    'requirements' => [],
                                ];
                                $currentPOIndex = count($purchaseOrders) - 1;
                            }

                            $purchaseOrders[$currentPOIndex]['references'][] = [
                                'segment_tag' => 'RFF',
                                'raw_segment' => $rff['raw_segment'],
                                'order_reference_value' => $rff['value'],
                                'reference_type_code' => $rff['qualifier'], // lookup -> reference_type_id
                            ];

                            break;
                        }

                        // -----------------
                        // CTA -> SERVICE_CONTACTS (cuelga de service_id y purchase_order_id)
                        // -----------------
                    case 'CTA': {
                            $cta = self::parseCTA($parts, $seg);
                            if (!$cta) break;

                            if ($currentPOIndex === null) {
                                // Igual que arriba: crea PO dummy para no perder el contacto
                                $purchaseOrders[] = [
                                    'segment_tag' => 'CNI',
                                    'raw_segment' => null,
                                    'purchase_order_secuence' => null,
                                    'purchase_order_number' => $file['purchase_order'],
                                    'items' => [],
                                    'references' => [],
                                    'contacts' => [],
                                    'measurements' => [],
                                    'requirements' => [],
                                ];
                                $currentPOIndex = count($purchaseOrders) - 1;
                            }

                            $purchaseOrders[$currentPOIndex]['contacts'][] = [
                                'segmemt_tag' => 'CTA', // tu tabla tiene typo segmemt_tag
                                'raw_segment' => $cta['raw_segment'],
                                'contact_name' => $cta['contact_name'],
                                'contact_type_code' => $cta['qualifier'], // lookup -> contact_type_id
                            ];

                            break;
                        }

                        // -----------------
                        // CNT -> SERVICE_MEASUREMENTS (cuelga de service_id y purchase_order_id)
                        // -----------------
                    case 'CNT': {
                            $cnt = self::parseCNT($parts, $seg);
                            if (!$cnt) break;

                            if ($currentPOIndex === null) {
                                $purchaseOrders[] = [
                                    'segment_tag' => 'CNI',
                                    'raw_segment' => null,
                                    'purchase_order_secuence' => null,
                                    'purchase_order_number' => $file['purchase_order'],
                                    'items' => [],
                                    'references' => [],
                                    'contacts' => [],
                                    'measurements' => [],
                                    'requirements' => [],
                                ];
                                $currentPOIndex = count($purchaseOrders) - 1;
                            }

                            $purchaseOrders[$currentPOIndex]['measurements'][] = [
                                'segment_tag' => 'CNT',
                                'raw_segment' => $cnt['raw_segment'],
                                'measure_value' => $cnt['value'],
                                'measure_unit' => $cnt['unit'],
                                'global_measure_type_code' => $cnt['qualifier'], // lookup -> global_measure_type_id
                            ];

                            break;
                        }

                        // Requerimientos: tu tabla purchase_order_requirements tiene campos NOT NULL.
                        // Solo los llenamos si el segmento trae esa estructura (si no, NO inventamos).
                        // case '...': parseRequirements...

                }
            } catch (\Throwable $e) {
                Log::error("[EdifactParser][$tag] Error: " . $e->getMessage());
            }
        }

        return [
            'file' => $file,
            'service' => $service, // luego el Job crea Service y obtiene service_id
            'service_dates' => $serviceDates,
            'service_equipment' => $serviceEquipment,
            'purchase_orders' => $purchaseOrders,
        ];
    }

    // -------------------------
    // Segment parsers (DB-friendly)
    // -------------------------

    private static function parseUNB(array $parts, string $raw): array
    {
        $senderRaw = $parts[2] ?? null;
        $receiverRaw = $parts[3] ?? null;

        $senderId = $senderRaw ? (explode(':', $senderRaw)[0] ?? null) : null;
        $receiverId = $receiverRaw ? (explode(':', $receiverRaw)[0] ?? null) : null;

        $dt = isset($parts[4]) ? self::formatUNBDateTime($parts[4]) : null;

        return [
            'raw_segment' => $raw,
            'transmission_id' => $parts[5] ?? null,
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'sended_at' => $dt ? substr($dt, 0, 10) : null,
        ];
    }

    private static function parseUNH(array $parts, string $raw): array
    {
        $msgId = $parts[1] ?? null;
        $msgType = isset($parts[2]) ? (explode(':', $parts[2])[0] ?? null) : null;

        return [
            'raw_segment' => $raw,
            'message_id' => $msgId,
            'message_type' => $msgType,
        ];
    }

    private static function parseBGM(array $parts, string $raw): array
    {
        return [
            'raw_segment' => $raw,
            'document_code' => $parts[1] ?? null,
            'document_number' => $parts[2] ?? null,
            'message_function_code' => $parts[3] ?? null,
        ];
    }

    private static function parseDTM(array $parts, string $raw): ?array
    {
        if (!isset($parts[1])) return null;

        $e = explode(':', $parts[1]);
        $qualifier = $e[0] ?? null;
        $value = $e[1] ?? null;
        $formatCode = $e[2] ?? null;

        if (!$qualifier || !$value || !$formatCode) return null;

        $dt = self::interpretDateByFormat($value, $formatCode);
        if (!$dt) return null;

        return [
            'raw_segment' => $raw,
            'qualifier' => $qualifier,
            'format_code' => (int)$formatCode,
            'date' => substr($dt, 0, 10),
            'mapped_field' => self::DTM_QUALIFIER_MAP[$qualifier] ?? null,
        ];
    }

    private static function parseCNI(array $parts, string $raw): array
    {
        return [
            'raw_segment' => $raw,
            'sequence' => $parts[1] ?? null,
            'consignment_reference_number' => $parts[2] ?? null,
        ];
    }

    private static function parseGID(array $parts, string $raw): ?array
    {
        $itemNumber = $parts[1] ?? null;
        if (!$itemNumber) return null;

        $pkgRaw = $parts[2] ?? '';
        $x = explode(':', $pkgRaw);

        $count = $x[0] ?? null;
        $type = $x[1] ?? null;

        // Tu tabla exige NOT NULL: item_count e item_type. Si no vienen, no invento: lo marco 0 / 'UNK'
        // Si prefieres fallar duro, cambia esto por "return null" cuando falte.
        if ($count === null) $count = 0;
        if (!$type) $type = 'UNK';

        return [
            'raw_segment' => $raw,
            'item_number' => $itemNumber,
            'item_count' => $count,
            'item_type' => $type,
        ];
    }

    private static function parseEQD(array $parts, string $raw): ?array
    {
        $equipmentType = $parts[1] ?? null;
        if (!$equipmentType) return null;

        return [
            'raw_segment' => $raw,
            'equipment_type_code' => $equipmentType,
            'equipment_identifier' => $parts[2] ?? null,
        ];
    }

    private static function parseRFF(array $parts, string $raw): ?array
    {
        // RFF+QUAL:VALUE
        if (!isset($parts[1])) return null;

        $e = explode(':', $parts[1]);
        $qual = $e[0] ?? null;
        $val = $e[1] ?? null;
        if (!$qual) return null;

        return [
            'raw_segment' => $raw,
            'qualifier' => $qual,
            'value' => $val,
        ];
    }

    private static function parseCTA(array $parts, string $raw): ?array
    {
        // CTA+QUAL+NAME
        $qual = $parts[1] ?? null;
        if (!$qual) return null;

        return [
            'raw_segment' => $raw,
            'qualifier' => $qual,
            'contact_name' => $parts[2] ?? null,
        ];
    }

    private static function parseCNT(array $parts, string $raw): ?array
    {
        // CNT+QUAL:VALUE:UNIT (en la práctica varía, así que lo tolero)
        if (!isset($parts[1])) return null;

        $e = explode(':', $parts[1]);
        $qual = $e[0] ?? null;
        $val = $e[1] ?? null;
        $unit = $e[2] ?? null;

        if (!$qual) return null;

        return [
            'raw_segment' => $raw,
            'qualifier' => $qual,
            'value' => $val !== null ? (float)$val : null,
            'unit' => $unit,
        ];
    }

    // -------------------------
    // Helpers
    // -------------------------

    private static function splitSegments(string $content): array
    {
        $segments = preg_split("/'\\s*/", trim($content));
        $segments = array_map('trim', $segments);
        return array_values(array_filter($segments, fn($s) => $s !== ''));
    }

    private static function interpretDateByFormat(string $value, string $formatCode): ?string
    {
        return match ($formatCode) {
            '102' => self::formatGeneralDate($value, 'Ymd'),
            '203' => self::formatGeneralDate($value, 'YmdHi'),
            '303' => self::formatGeneralDate($value, 'Hi'),
            '718' => self::formatUNBDateTime($value),
            default => null,
        };
    }

    private static function formatGeneralDate(string $value, string $format): ?string
    {
        return \DateTime::createFromFormat($format, $value)?->format('Y-m-d H:i:s');
    }

    private static function formatUNBDateTime(string $dateHour): ?string
    {
        if (!str_contains($dateHour, ':')) return null;

        [$date, $hour] = explode(':', $dateHour, 2);
        if (strlen($date) < 6 || strlen($hour) < 4) return null;

        $year = '20' . substr($date, 0, 2);
        $month = substr($date, 2, 2);
        $day = substr($date, 4, 2);

        $h = substr($hour, 0, 2);
        $m = substr($hour, 2, 2);

        $dt = \DateTime::createFromFormat('Y-m-d H:i', "{$year}-{$month}-{$day} {$h}:{$m}");
        return $dt?->format('Y-m-d H:i:s');
    }
}
