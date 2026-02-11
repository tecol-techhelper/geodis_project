<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class EdifactParser
{
    private const DTM_QUALIFIER_MAP = [
        '137' => 'recived_at',
        '11'  => 'despatch_date',
        '132' => 'arrival_date',
        '2'   => 'delivery_date',
        '81'  => 'other_date',
    ];

    public static function parseForDatabase(string $content, ?string $fileName = null): array
    {
        $segments = self::splitSegments($content);

        // Message-level dates (DTM+137, etc)
        $messageDates = [];

        // Service-level
        $service = null;
        $serviceDates = [];
        $serviceEquipment = [];

        $serviceContacts = [];
        $serviceMeasurements = [];
        $serviceParties = [];
        $serviceLocations = [];
        $transportDetails = [];

        // Purchase Orders
        $purchaseOrders = [];
        $currentPOIndex = null;
        $currentItemIndex = null;

        // Contact context for COM
        $lastContactContext = null; // ['scope'=>'service'|'po', 'poIndex'=>?, 'contactIndex'=>?]

        // Transport charge pairing (TCC + PRI) inside a PO
        $pendingTCC = null;

        $file = [
            'transmission_id' => null,
            'message_type' => null,
            'file_name' => $fileName,
            'purchase_order' => null, // concatenated CNI numbers
            'recived_at' => null,     // from DTM+137
            'sended_at' => null,      // from UNB
        ];

        // Track PO numbers to build edifact_files.purchase_order
        $poNumbers = [];

        foreach ($segments as $seg) {
            $parts = explode('+', $seg);
            $tag = $parts[0] ?? null;
            if (!$tag) {
                continue;
            }

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
                            $service = [
                                'segment_tag' => 'BGM',
                                'raw_segment' => $bgm['raw_segment'],
                                'document_code' => $bgm['document_code'],
                                'document_number' => $bgm['document_number'],
                                'message_function_code' => $bgm['message_function_code'],
                            ];
                            break;
                        }

                        // -----------------
                        // DTM -> message_dates OR service_dates
                        // -----------------
                    case 'DTM': {
                            $dtm = self::parseDTM($parts, $seg);
                            if (!$dtm) break;

                            // DTM+137 is MESSAGE context (not service context)
                            if (($dtm['qualifier'] ?? null) === '137') {
                                if (!$file['recived_at']) {
                                    $file['recived_at'] = $dtm['date'];
                                }

                                $messageDates[] = [
                                    'segment_tag' => 'DTM',
                                    'raw_segment' => $dtm['raw_segment'],
                                    'date' => $dtm['date'],
                                    'format_date' => $dtm['format_code'],
                                    'date_type_code' => $dtm['qualifier'],
                                ];
                                break;
                            }

                            // everything else is service_dates (as per your current model)
                            $serviceDates[] = [
                                'segment_tag' => 'DTM',
                                'raw_segment' => $dtm['raw_segment'],
                                'service_date' => $dtm['date'],
                                'format_date' => $dtm['format_code'],
                                'date_type_code' => $dtm['qualifier'],
                            ];

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
                                'equipment_type_code' => $eqd['equipment_type_code'],
                            ];
                            break;
                        }

                        // -----------------
                        // LOC -> service_locations OR purchase_orders[n].locations
                        // -----------------
                    case 'LOC': {
                            $loc = self::parseLOC($parts, $seg);
                            if (!$loc) break;

                            $row = [
                                'segment_tag' => 'LOC',
                                'raw_segment' => $loc['raw_segment'],
                                'location_code' => $loc['location_code'],
                                'location_details' => $loc['location_details'],
                            ];

                            if ($currentPOIndex !== null) {
                                $purchaseOrders[$currentPOIndex]['locations'][] = $row;
                            } else {
                                $serviceLocations[] = $row;
                            }
                            break;
                        }

                        // -----------------
                        // TDT -> transport_details (service-level)
                        // -----------------
                    case 'TDT': {
                            $tdt = self::parseTDT($parts, $seg);
                            if (!$tdt) break;

                            $transportDetails[] = [
                                'segment_tag' => 'TDT',
                                'raw_segment' => $tdt['raw_segment'],
                                'vehicle_details' => $tdt['vehicle_details'],
                                'transport_stage_code' => $tdt['transport_stage_code'],
                                'transport_mode_code' => $tdt['transport_mode_code'], // can be null
                            ];
                            break;
                        }

                        // -----------------
                        // NAD -> parties (service-level or PO-level)
                        // -----------------
                    case 'NAD': {
                            $nad = self::parseNAD($parts, $seg);
                            if (!$nad) break;

                            $row = [
                                'segment_tag' => 'NAD',
                                'raw_segment' => $nad['raw_segment'],
                                'party_name' => $nad['party_name'],
                                'party_street' => $nad['party_street'],
                                'party_city' => $nad['party_city'],
                                'party_region' => $nad['party_region'],
                                'party_country_code' => $nad['party_country_code'],
                                'party_type_code' => $nad['party_type_code'],
                            ];

                            if ($currentPOIndex !== null) {
                                $purchaseOrders[$currentPOIndex]['parties'][] = $row;
                            } else {
                                $serviceParties[] = $row;
                            }
                            break;
                        }

                        // -----------------
                        // CNI -> PURCHASE_ORDERS (open PO)
                        // -----------------
                    case 'CNI': {
                            $cni = self::parseCNI($parts, $seg);

                            $purchaseOrders[] = [
                                'segment_tag' => 'CNI',
                                'raw_segment' => $cni['raw_segment'],
                                'purchase_order_secuence' => $cni['sequence'] !== null ? (int)$cni['sequence'] : null,
                                'purchase_order_number' => $cni['consignment_reference_number'],

                                'items' => [],
                                'references' => [],
                                'contacts' => [],
                                'measurements' => [],
                                'requirements' => [],
                                'delivery_terms' => [],
                                'transport_charges' => [],
                                'notes' => [],
                                'parties' => [],
                                'locations' => [],
                            ];

                            $currentPOIndex = count($purchaseOrders) - 1;
                            $currentItemIndex = null;
                            $pendingTCC = null;
                            $lastContactContext = null;

                            if (!empty($cni['consignment_reference_number'])) {
                                $poNumbers[] = $cni['consignment_reference_number'];
                            }

                            break;
                        }

                        // -----------------
                        // GID -> PURCHASE_ORDER_ITEMS (open item)
                        // -----------------
                    case 'GID': {
                            $gid = self::parseGID($parts, $seg);
                            if (!$gid) break;

                            if ($currentPOIndex === null) {
                                $purchaseOrders[] = [
                                    'segment_tag' => 'CNI',
                                    'raw_segment' => null,
                                    'purchase_order_secuence' => null,
                                    'purchase_order_number' => null,
                                    'items' => [],
                                    'references' => [],
                                    'contacts' => [],
                                    'measurements' => [],
                                    'requirements' => [],
                                    'delivery_terms' => [],
                                    'transport_charges' => [],
                                    'notes' => [],
                                    'parties' => [],
                                    'locations' => [],
                                ];
                                $currentPOIndex = count($purchaseOrders) - 1;
                            }

                            $purchaseOrders[$currentPOIndex]['items'][] = [
                                'segment_tag' => 'GID',
                                'raw_segment' => $gid['raw_segment'],
                                'item_number' => (int)$gid['item_number'],
                                'item_count' => (int)$gid['item_count'],
                                'item_type' => (string)$gid['item_type'],

                                'products' => [],
                                'packages' => [],
                                'identifiers' => [],
                                'measurements' => [],
                                'dimensions' => [],
                                'notes' => [],
                            ];

                            $currentItemIndex = count($purchaseOrders[$currentPOIndex]['items']) - 1;
                            $lastContactContext = null;
                            break;
                        }

                        // -----------------
                        // RFF -> ORDER_REFERENCES (PO-level)
                        // -----------------
                    case 'RFF': {
                            $rff = self::parseRFF($parts, $seg);
                            if (!$rff) break;
                            if ($currentPOIndex === null) break;

                            $purchaseOrders[$currentPOIndex]['references'][] = [
                                'segment_tag' => 'RFF',
                                'raw_segment' => $rff['raw_segment'],
                                'order_reference_value' => $rff['value'],
                                'reference_type_code' => $rff['qualifier'],
                            ];
                            break;
                        }

                        // -----------------
                        // CTA -> service_contacts OR purchase_orders[n].contacts
                        // -----------------
                    case 'CTA': {
                            $cta = self::parseCTA($parts, $seg);
                            if (!$cta) break;

                            $row = [
                                'segment_tag' => 'CTA',
                                'raw_segment' => $cta['raw_segment'],
                                'contact_name' => $cta['contact_name'],
                                'contact_type_code' => $cta['qualifier'],
                                'details' => [],
                            ];

                            if ($currentPOIndex !== null) {
                                $purchaseOrders[$currentPOIndex]['contacts'][] = $row;
                                $lastContactContext = [
                                    'scope' => 'po',
                                    'poIndex' => $currentPOIndex,
                                    'contactIndex' => count($purchaseOrders[$currentPOIndex]['contacts']) - 1,
                                ];
                            } else {
                                $serviceContacts[] = $row;
                                $lastContactContext = [
                                    'scope' => 'service',
                                    'poIndex' => null,
                                    'contactIndex' => count($serviceContacts) - 1,
                                ];
                            }

                            break;
                        }

                        // -----------------
                        // COM -> contact_details for last CTA (service or PO)
                        // -----------------
                    case 'COM': {
                            $com = self::parseCOM($parts, $seg);
                            if (!$com || !$lastContactContext) break;

                            $detail = [
                                'segment_tag' => 'COM',
                                'raw_segment' => $com['raw_segment'],
                                'channel_contact' => $com['channel_contact'],
                                'contact_information' => $com['contact_information'],
                            ];

                            if ($lastContactContext['scope'] === 'service') {
                                $idx = $lastContactContext['contactIndex'];
                                $serviceContacts[$idx]['details'][] = $detail;
                            } else {
                                $po = $lastContactContext['poIndex'];
                                $idx = $lastContactContext['contactIndex'];
                                $purchaseOrders[$po]['contacts'][$idx]['details'][] = $detail;
                            }

                            break;
                        }

                        // -----------------
                        // CNT -> service_measurements OR purchase_orders[n].measurements
                        // -----------------
                    case 'CNT': {
                            $cnt = self::parseCNT($parts, $seg);
                            if (!$cnt) break;

                            $row = [
                                'segment_tag' => 'CNT',
                                'raw_segment' => $cnt['raw_segment'],
                                'measure_value' => $cnt['value'],
                                'measure_unit' => $cnt['unit'],
                                'global_measure_type_code' => $cnt['qualifier'],
                            ];

                            if ($currentPOIndex !== null) {
                                $purchaseOrders[$currentPOIndex]['measurements'][] = $row;
                            } else {
                                $serviceMeasurements[] = $row;
                            }

                            break;
                        }

                        // -----------------
                        // FTX -> PO notes OR Item notes (by context)
                        // -----------------
                    case 'FTX': {
                            $ftx = self::parseFTX($parts, $seg);
                            if (!$ftx) break;

                            $row = [
                                'segment_tag' => 'FTX',
                                'raw_segment' => $ftx['raw_segment'],
                                'note_type_code' => $ftx['note_type_code'],
                                'note_text' => $ftx['note_text'],
                            ];

                            if ($currentPOIndex !== null && $currentItemIndex !== null) {
                                $purchaseOrders[$currentPOIndex]['items'][$currentItemIndex]['notes'][] = $row;
                            } elseif ($currentPOIndex !== null) {
                                $purchaseOrders[$currentPOIndex]['notes'][] = $row;
                            }

                            break;
                        }

                        // -----------------
                        // TOD -> delivery_terms (PO-level)
                        // -----------------
                    case 'TOD': {
                            $tod = self::parseTOD($parts, $seg);
                            if (!$tod) break;
                            if ($currentPOIndex === null) break;

                            $purchaseOrders[$currentPOIndex]['delivery_terms'][] = [
                                'segment_tag' => 'TOD',
                                'raw_segment' => $tod['raw_segment'],
                                'freight_payment_code' => $tod['freight_payment_code'],
                                'delivery_term_function' => $tod['delivery_term_function'],
                                'delivery_term_catalog_code' => $tod['delivery_term_catalog_code'],
                            ];
                            break;
                        }

                        // -----------------
                        // TSR -> purchase_order_requirements (PO-level)
                        // -----------------
                    case 'TSR': {
                            $tsr = self::parseTSR($parts, $seg);
                            if (!$tsr) break;
                            if ($currentPOIndex === null) break;

                            $purchaseOrders[$currentPOIndex]['requirements'][] = [
                                'segment_tag' => 'TSR',
                                'raw_segment' => $tsr['raw_segment'],
                                'contract_carriage_condition_code' => $tsr['contract_carriage_condition_code'],
                                'po_requirements_code' => $tsr['po_requirements_code'],
                                'additional_po_requirement_code' => $tsr['additional_po_requirement_code'],
                                'transport_priority' => $tsr['transport_priority'],
                            ];
                            break;
                        }

                        // -----------------
                        // TCC -> pending transport charge (PO-level)
                        // -----------------
                    case 'TCC': {
                            $tcc = self::parseTCC($parts, $seg);
                            if (!$tcc) break;
                            if ($currentPOIndex === null) break;

                            $pendingTCC = $tcc;
                            break;
                        }

                        // -----------------
                        // PRI -> finalize transport charge with last TCC (PO-level)
                        // -----------------
                    case 'PRI': {
                            $pri = self::parsePRI($parts, $seg);
                            if (!$pri) break;
                            if ($currentPOIndex === null) break;

                            $tcc = $pendingTCC ?? [
                                'raw_segment' => '',
                                'charge_code' => 'UNK',
                                'rate_class_code' => 'UNK',
                            ];

                            $purchaseOrders[$currentPOIndex]['transport_charges'][] = [
                                'charge_code' => $tcc['charge_code'],
                                'rate_class_code' => $pri['rate_class_code'] ?? $tcc['rate_class_code'] ?? 'UNK',
                                'price_amount' => $pri['price_amount'],
                                'unit_price_basis' => $pri['unit_price_basis'],
                                'measure_unit_code' => $pri['measure_unit_code'],
                                'pri_segment_raw' => $pri['raw_segment'],
                                'tcc_segment_raw' => $tcc['raw_segment'],
                                'price_qualifier_code' => $pri['price_qualifier_code'],
                            ];

                            // consume pairing so next PRI doesn't reuse old TCC
                            $pendingTCC = null;

                            break;
                        }

                        // -----------------
                        // Item-level segments
                        // -----------------
                    case 'PIA': {
                            $pia = self::parsePIA($parts, $seg);
                            if (!$pia) break;
                            if ($currentPOIndex === null || $currentItemIndex === null) break;

                            $purchaseOrders[$currentPOIndex]['items'][$currentItemIndex]['products'][] = [
                                'segment_tag' => 'PIA',
                                'raw_segment' => $pia['raw_segment'],
                                'role_code' => $pia['role_code'],
                                'identifier_value' => $pia['identifier_value'],
                                'identifier_type_code' => $pia['identifier_type_code'],
                            ];
                            break;
                        }

                    case 'PCI': {
                            $pci = self::parsePCI($parts, $seg);
                            if (!$pci) break;
                            if ($currentPOIndex === null || $currentItemIndex === null) break;

                            $purchaseOrders[$currentPOIndex]['items'][$currentItemIndex]['packages'][] = [
                                'segment_tag' => 'PCI',
                                'raw_segment' => $pci['raw_segment'],
                                'identifier_type_code' => $pci['identifier_type_code'],
                                'package_identifier_value' => $pci['package_identifier_value'],
                            ];
                            break;
                        }

                    case 'GIN': {
                            $gin = self::parseGIN($parts, $seg);
                            if (!$gin) break;
                            if ($currentPOIndex === null || $currentItemIndex === null) break;

                            $purchaseOrders[$currentPOIndex]['items'][$currentItemIndex]['identifiers'][] = [
                                'segment_tag' => 'GIN',
                                'raw_segment' => $gin['raw_segment'],
                                'identifier_qualifier' => $gin['identifier_qualifier'],
                                'identifier_value' => $gin['identifier_value'],
                            ];
                            break;
                        }

                    case 'MEA': {
                            $mea = self::parseMEA($parts, $seg);
                            if (!$mea) break;
                            if ($currentPOIndex === null || $currentItemIndex === null) break;

                            $purchaseOrders[$currentPOIndex]['items'][$currentItemIndex]['measurements'][] = [
                                'segment_tag' => 'MEA',
                                'raw_segment' => $mea['raw_segment'],
                                'purpose_code' => $mea['purpose_code'],
                                'attribute_code' => $mea['attribute_code'],
                                'unit' => $mea['unit'],
                                'value' => $mea['value'],
                            ];
                            break;
                        }

                    case 'DIM': {
                            $dim = self::parseDIM($parts, $seg);
                            if (!$dim) break;
                            if ($currentPOIndex === null || $currentItemIndex === null) break;

                            $purchaseOrders[$currentPOIndex]['items'][$currentItemIndex]['dimensions'][] = [
                                'segment_tag' => 'DIM',
                                'raw_segment' => $dim['raw_segment'],
                                'dimension_type_code' => $dim['dimension_type_code'],
                                'unit' => $dim['unit'],
                                'length' => $dim['length'],
                                'width' => $dim['width'],
                                'height' => $dim['height'],
                            ];
                            break;
                        }
                }
            } catch (\Throwable $e) {
                Log::error("[EdifactParser][$tag] Error: " . $e->getMessage());
            }
        }

        // edifact_files.purchase_order should be the concatenated PO numbers
        if (!empty($poNumbers)) {
            $file['purchase_order'] = implode(' ', array_values(array_unique($poNumbers)));
        }

        return [
            'file' => $file,

            'service' => $service,
            'message_dates' => $messageDates,

            'service_dates' => $serviceDates,
            'service_equipment' => $serviceEquipment,

            'service_contacts' => $serviceContacts,
            'service_measurements' => $serviceMeasurements,
            'service_parties' => $serviceParties,
            'service_locations' => $serviceLocations,
            'transport_details' => $transportDetails,

            'purchase_orders' => $purchaseOrders,
        ];
    }

    // -------------------------
    // Segment parsers
    // -------------------------

    private static function parseUNB(array $parts, string $raw): array
    {
        $dt = isset($parts[4]) ? self::formatUNBDateTime($parts[4]) : null;

        return [
            'raw_segment' => $raw,
            'transmission_id' => $parts[5] ?? null,
            'sended_at' => $dt ? substr($dt, 0, 10) : null,
        ];
    }

    private static function parseUNH(array $parts, string $raw): array
    {
        $msgType = isset($parts[2]) ? (explode(':', $parts[2])[0] ?? null) : null;

        return [
            'raw_segment' => $raw,
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

        $dt = self::interpretDateByFormat($value, (string)$formatCode);
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

        $count = $x[0] ?? 0;
        $type = $x[1] ?? 'UNK';

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
        $qual = $parts[1] ?? null;
        if (!$qual) return null;

        return [
            'raw_segment' => $raw,
            'qualifier' => $qual,
            'contact_name' => $parts[2] ?? null,
        ];
    }

    private static function parseCOM(array $parts, string $raw): ?array
    {
        if (!isset($parts[1])) return null;

        $e = explode(':', $parts[1]);
        $value = $e[0] ?? null;
        $channel = $e[1] ?? null;

        if (!$channel) return null;

        return [
            'raw_segment' => $raw,
            'channel_contact' => trim($channel),
            'contact_information' => $value !== null ? trim($value) : null,
        ];
    }

    private static function parseCNT(array $parts, string $raw): ?array
    {
        if (!isset($parts[1])) return null;

        $e = explode(':', $parts[1]);
        $qual = $e[0] ?? null;
        $val = $e[1] ?? null;
        $unit = $e[2] ?? null;

        $val = str_replace(',',  '.', $val);

        if (!$qual) return null;

        return [
            'raw_segment' => $raw,
            'qualifier' => $qual,
            'value' => $val !== null ? (float)$val : null,
            'unit' => $unit,
        ];
    }

    private static function parseNAD(array $parts, string $raw): ?array
    {
        $qual = $parts[1] ?? null;
        if (!$qual) return null;

        $name = $parts[3] ?? null;
        $street = $parts[4] ?? null;

        $city = $parts[6] ?? null;
        $region = $parts[8] ?? null;
        $country = $parts[9] ?? null;

        $city = trim((string)$city);
        $region = trim((string)$region);
        $country = trim((string)$country);

        if ($city === '') $city = 'UNKNOWN';
        if ($region === '') $region = 'UNKNOWN';
        if ($country === '') $country = 'UN';

        return [
            'raw_segment' => $raw,
            'party_type_code' => trim($qual),
            'party_name' => $name ? trim($name) : null,
            'party_street' => $street ? trim($street) : null,
            'party_city' => $city,
            'party_region' => $region,
            'party_country_code' => $country,
        ];
    }

    private static function parseLOC(array $parts, string $raw): ?array
    {
        $code = $parts[1] ?? null;
        $detail = $parts[2] ?? null;
        if ($code === null) return null;

        $detail = trim((string)$detail);
        if ($detail === '') $detail = 'UNKNOWN';

        return [
            'raw_segment' => $raw,
            'location_code' => (int)$code,
            'location_details' => $detail,
        ];
    }

    /**
     * FIX: TDT can be short: "TDT+20'" (no mode)
     * Also common: "TDT+20++3'" (mode at index 3)
     */
    private static function parseTDT(array $parts, string $raw): ?array
    {
        $stageRaw = $parts[1] ?? null;
        if ($stageRaw === null || trim((string)$stageRaw) === '') {
            return null;
        }

        // mode may be missing in short TDT (e.g., TDT+20')
        $modeRaw = $parts[3] ?? null;
        $mode = null;
        if ($modeRaw !== null && trim((string)$modeRaw) !== '') {
            $mode = (int)$modeRaw;
        }

        // Vehicle details can be in parts[2] or parts[4], but may be absent
        $vehicle = trim((string)($parts[2] ?? ''));
        if ($vehicle === '') {
            $vehicle = trim((string)($parts[4] ?? ''));
        }
        if ($vehicle === '') {
            $vehicle = 'UNKNOWN';
        }

        return [
            'raw_segment' => $raw,
            'transport_stage_code' => (int)$stageRaw,
            'transport_mode_code' => $mode, // nullable
            'vehicle_details' => $vehicle,
        ];
    }

    private static function parseFTX(array $parts, string $raw): ?array
    {
        $type = $parts[1] ?? null;
        if (!$type) return null;

        $candidates = array_slice($parts, 2);
        $text = null;

        for ($i = count($candidates) - 1; $i >= 0; $i--) {
            $v = trim((string)$candidates[$i]);
            if ($v !== '') {
                $text = $v;
                break;
            }
        }

        if ($text !== null) {
            $text = preg_replace('/^\:+\s*/', '', $text);
        }

        return [
            'raw_segment' => $raw,
            'note_type_code' => trim($type),
            'note_text' => $text,
        ];
    }

    private static function parseTOD(array $parts, string $raw): ?array
    {
        $function = $parts[1] ?? null;
        if ($function === null) return null;

        $freightPayment = $parts[2] ?? null;
        $termCode = $parts[3] ?? null;

        $termCode = $termCode ? trim($termCode) : null;

        return [
            'raw_segment' => $raw,
            'freight_payment_code' => $freightPayment ? trim($freightPayment) : null,
            'delivery_term_function' => (int)$function,
            'delivery_term_catalog_code' => $termCode ?? 'UNK',
        ];
    }

    private static function parseTSR(array $parts, string $raw): ?array
    {
        $a = $parts[1] ?? null;
        if ($a === null) return null;

        return [
            'raw_segment' => $raw,
            'contract_carriage_condition_code' => trim((string)($parts[1] ?? 'UNK')),
            'po_requirements_code' => trim((string)($parts[2] ?? 'UNK')),
            'additional_po_requirement_code' => ($parts[3] ?? null) !== null ? trim((string)$parts[3]) : null,
            'transport_priority' => trim((string)($parts[4] ?? $parts[1] ?? 'UNK')),
        ];
    }

    private static function parseTCC(array $parts, string $raw): ?array
    {
        if (!isset($parts[1])) return null;

        $e = explode(':', $parts[1]);
        $charge = trim((string)($e[0] ?? ''));
        $rate = trim((string)($e[1] ?? ''));

        if ($charge === '') $charge = 'UNK';
        if ($rate === '') $rate = 'UNK';

        return [
            'raw_segment' => $raw,
            'charge_code' => $charge,
            'rate_class_code' => $rate,
        ];
    }

    private static function parsePRI(array $parts, string $raw): ?array
    {
        if (!isset($parts[1])) return null;

        $e = explode(':', $parts[1]);
        $qual = $e[0] ?? null;
        if (!$qual) return null;

        $amount = $e[1] ?? null;
        $rateClass = $e[2] ?? null;
        $unitBasis = $e[4] ?? null;
        $unit = $e[5] ?? null;

        return [
            'raw_segment' => $raw,
            'price_qualifier_code' => trim((string)$qual),
            'price_amount' => $amount !== null ? (float)$amount : 0.0,
            'rate_class_code' => $rateClass ? trim($rateClass) : null,
            'unit_price_basis' => $unitBasis !== null ? (float)$unitBasis : null,
            'measure_unit_code' => $unit ? trim($unit) : null,
        ];
    }

    private static function parsePIA(array $parts, string $raw): ?array
    {
        $role = $parts[1] ?? null;
        if (!$role) return null;

        $identifierValue = null;
        $identifierType = null;

        if (isset($parts[2])) {
            $e = explode(':', $parts[2]);
            $identifierValue = $e[0] ?? null;
            $identifierType = $e[1] ?? null;
        }

        if (!$identifierType && isset($parts[3])) {
            $e2 = explode(':', $parts[3]);
            $identifierType = $e2[1] ?? ($e2[0] ?? null);
        }

        return [
            'raw_segment' => $raw,
            'role_code' => trim((string)$role),
            'identifier_value' => $identifierValue !== '' ? $identifierValue : null,
            'identifier_type_code' => $identifierType ? trim((string)$identifierType) : null,
        ];
    }

    private static function parsePCI(array $parts, string $raw): ?array
    {
        $type = $parts[1] ?? null;
        if (!$type) return null;

        return [
            'raw_segment' => $raw,
            'identifier_type_code' => trim((string)$type),
            'package_identifier_value' => isset($parts[2]) && trim((string)$parts[2]) !== '' ? trim((string)$parts[2]) : null,
        ];
    }

    private static function parseGIN(array $parts, string $raw): ?array
    {
        $qual = $parts[1] ?? null;
        if (!$qual) return null;

        return [
            'raw_segment' => $raw,
            'identifier_qualifier' => trim((string)$qual),
            'identifier_value' => isset($parts[2]) && trim((string)$parts[2]) !== '' ? trim((string)$parts[2]) : null,
        ];
    }

    private static function parseMEA(array $parts, string $raw): ?array
    {
        $purpose = $parts[1] ?? null;
        $attr = $parts[2] ?? null;
        if (!$purpose || !$attr || !isset($parts[3])) return null;

        $e = explode(':', $parts[3]);
        $unit = $e[0] ?? null;
        $val = $e[1] ?? null;

        return [
            'raw_segment' => $raw,
            'purpose_code' => trim((string)$purpose),
            'attribute_code' => trim((string)$attr),
            'unit' => $unit ? trim((string)$unit) : null,
            'value' => $val !== null ? (float)$val : null,
        ];
    }

    private static function parseDIM(array $parts, string $raw): ?array
    {
        $type = $parts[1] ?? null;
        if (!$type || !isset($parts[2])) return null;

        $e = explode(':', $parts[2]);
        $unit = $e[0] ?? null;
        $l = $e[1] ?? null;
        $w = $e[2] ?? null;
        $h = $e[3] ?? null;

        return [
            'raw_segment' => $raw,
            'dimension_type_code' => trim((string)$type),
            'unit' => $unit ? trim((string)$unit) : null,
            'length' => $l !== null ? (float)$l : null,
            'width' => $w !== null ? (float)$w : null,
            'height' => $h !== null ? (float)$h : null,
        ];
    }

    // -------------------------
    // Helpers
    // -------------------------

    /**
     * Segment separator is the apostrophe (') in these files.
     * Don't rely on \r\n. EDIFACT doesn't owe you line breaks.
     */
    private static function splitSegments(string $content): array
    {
        $content = trim($content);

        // Split by apostrophe + optional whitespace/newlines
        $segments = preg_split("/'\\s*/u", $content) ?: [];
        $segments = array_map('trim', $segments);

        // Remove empties
        return array_values(array_filter($segments, static fn($s) => $s !== ''));
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
