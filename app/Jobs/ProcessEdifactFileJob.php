<?php

namespace App\Jobs;

use App\Models\EdifactFile;
use App\Models\Notification;
use App\Services\EdifactParser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ProcessEdifactFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public function __construct(
        public string $fullpath,
        public string $fileName,
        public string $tipoMensaje,     // IFCSUM / IFTSTA (enum edifact_files.message_type)
        public string $relativePath
    ) {}

    public function handle(): void
    {
        if (!File::exists($this->fullpath)) {
            Log::error("File not found at path {$this->fullpath}");
            return;
        }

        $content = File::get($this->fullpath);

        // 1) Parse único
        $parsed = EdifactParser::parseForDatabase($content, $this->fileName);

        // 2) Validaciones mínimas (fatales)
        $file = $parsed['file'] ?? [];
        $service = $parsed['service'] ?? null;

        if (empty($file['transmission_id'])) {
            Log::warning("Archivo sin UNB/transmission_id: {$this->fileName}");
            return;
        }

        if (!$service || empty($service['raw_segment'])) {
            Log::error("Archivo sin BGM válido (services.raw_segment requerido): {$this->fileName}");
            return;
        }

        // 3) Duplicidad por transmission_id
        if (EdifactFile::where('transmission_id', $file['transmission_id'])->exists()) {
            Log::info("Transmisión ya procesada: {$file['transmission_id']} ({$this->fileName})");
            return;
        }

        // 4) Validar message_type (warning)
        if (!empty($file['message_type']) && $file['message_type'] !== $this->tipoMensaje) {
            Log::warning("message_type mismatch: UNH={$file['message_type']} job={$this->tipoMensaje} file={$this->fileName}");
        }

        DB::beginTransaction();

        try {
            $statusId = $this->defaultStatusId();

            // 5) Crear SERVICE
            $serviceId = DB::table('services')->insertGetId([
                'segment_tag' => $service['segment_tag'] ?? 'BGM',
                'raw_segment' => $service['raw_segment'],

                // Mapeo razonable BGM -> columnas existentes
                'item'        => $service['document_code'] ?? null,
                'consecutive' => $service['document_number'] ?? null,
                'observation' => isset($service['message_function_code'])
                    ? ('BGM function: ' . $service['message_function_code'])
                    : null,

                'status_id'   => $statusId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // 6) Crear EDIFACT_FILE
            $edifactFileId = DB::table('edifact_files')->insertGetId([
                'transmission_id' => $file['transmission_id'],
                'message_type'    => $this->tipoMensaje,
                'file_name'       => $this->fileName,
                'purchase_order'  => $file['purchase_order'] ?? null,
                'recived_at'      => $file['recived_at'] ?? null,
                'sended_at'       => $file['sended_at'] ?? null,
                'file_url'        => $this->relativePath,
                'file_path'       => $this->fullpath,
                'service_id'      => $serviceId,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            // 7) SERVICE_DATES
            foreach (($parsed['service_dates'] ?? []) as $dtm) {
                $dateTypeId = $this->getOrCreateDateTypeId($dtm['date_type_code'] ?? null);

                DB::table('service_dates')->insert([
                    'segment_tag'  => $dtm['segment_tag'] ?? 'DTM',
                    'raw_segment'  => $dtm['raw_segment'],
                    'service_date' => $dtm['service_date'],
                    'format_date'  => $dtm['format_date'] ?? null,
                    'service_id'   => $serviceId,
                    'date_type_id' => $dateTypeId,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            // 8) SERVICE_EQUIPMENT
            foreach (($parsed['service_equipment'] ?? []) as $eqd) {
                $equipmentTypeId = $this->getOrCreateEquipmentTypeId($eqd['equipment_type_code'] ?? null);

                DB::table('service_equipment')->insert([
                    'segment_tag'          => $eqd['segment_tag'] ?? 'EQD',
                    'raw_segment'          => $eqd['raw_segment'],
                    'equipment_identifier' => $eqd['equipment_identifier'] ?? null,
                    'equipment_type_id'    => $equipmentTypeId,
                    'service_id'           => $serviceId,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]);
            }

            // 9) TRANSPORT_DETAILS (TDT)
            foreach (($parsed['transport_details'] ?? []) as $tdt) {
                $stageId = $this->getOrCreateTransportStageId($tdt['transport_stage_code'] ?? null);

                // NOT NULL: transport_mode_id. Fallback a 6 (Multimodal) o primer id.
                $modeCode = $tdt['transport_mode_code'] ?? null;
                if ($modeCode === null) {
                    $modeId = (int)(
                        DB::table('transport_modes')->where('transport_mode_code', 6)->value('id')
                        ?? DB::table('transport_modes')->value('id')
                        ?? 1
                    );
                } else {
                    $modeId = $this->getOrCreateTransportModeId((int)$modeCode);
                }

                DB::table('transport_details')->insert([
                    'segment_tag'        => $tdt['segment_tag'] ?? 'TDT',
                    'raw_segment'        => $tdt['raw_segment'],
                    'vehicle_details'    => $tdt['vehicle_details'] ?? 'UNKNOWN',
                    'transport_stage_id' => $stageId,
                    'transport_mode_id'  => $modeId,
                    'service_id'         => $serviceId,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            // 10) LOCATION_DETAILS (service-level)
            $allLocs = array_merge(($parsed['service_locations'] ?? []), ...array_map(
                fn($po) => $po['locations'] ?? [],
                $parsed['purchase_orders'] ?? []
            ));

            foreach ($allLocs as $loc) {
                $locationCodeId = $this->getOrCreateLocationCodeId($loc['location_code'] ?? null);

                DB::table('location_details')->insert([
                    'segment_tag'       => $loc['segment_tag'] ?? 'LOC',
                    'raw_segment'       => $loc['raw_segment'],
                    'location_details'  => $loc['location_details'] ?? 'UNKNOWN',
                    'location_code_id'  => $locationCodeId,
                    'service_id'        => $serviceId,
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);
            }

            // 11) SERVICE CONTACTS (CTA) + DETAILS (COM) a nivel SERVICE
            foreach (($parsed['service_contacts'] ?? []) as $contact) {
                $contactTypeId = $this->getOrCreateContactTypeId($contact['contact_type_code'] ?? null);

                $serviceContactId = DB::table('service_contacts')->insertGetId([
                    'segment_tag'     => $contact['segment_tag'] ?? 'CTA',
                    'contact_name'    => $contact['contact_name'] ?? null,
                    'raw_segment'     => $contact['raw_segment'],
                    'service_id'      => $serviceId,
                    'contact_type_id' => $contactTypeId,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                foreach (($contact['details'] ?? []) as $detail) {
                    DB::table('service_contact_details')->insert([
                        'segment_tag'         => $detail['segment_tag'] ?? 'COM',
                        'channel_contact'     => $detail['channel_contact'],
                        'contact_information' => $detail['contact_information'],
                        'raw_segment'         => $detail['raw_segment'],
                        'service_contact_id'  => $serviceContactId,
                        'created_at'          => now(),
                        'updated_at'          => now(),
                    ]);
                }
            }

            // 12) SERVICE MEASUREMENTS (CNT) a nivel SERVICE
            foreach (($parsed['service_measurements'] ?? []) as $m) {
                $globalMeasureTypeId = $this->getOrCreateGlobalMeasureTypeId($m['global_measure_type_code'] ?? null);

                DB::table('service_measurements')->insert([
                    'segment_tag'            => $m['segment_tag'] ?? 'CNT',
                    'measure_value'          => $m['measure_value'] ?? null,
                    'measure_unit'           => $m['measure_unit'] ?? null,
                    'raw_segment'            => $m['raw_segment'],
                    'service_id'             => $serviceId,
                    'global_measure_type_id' => $globalMeasureTypeId,
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ]);
            }

            // 13) SERVICE PARTIES (NAD) a nivel SERVICE
            foreach (($parsed['service_parties'] ?? []) as $party) {
                $partyTypeId = $this->getOrCreatePartyTypeId($party['party_type_code'] ?? null);

                DB::table('service_parties')->insert([
                    'segment_tag'        => $party['segment_tag'] ?? 'NAD',
                    'raw_segment'        => $party['raw_segment'],
                    'party_name'         => $party['party_name'] ?? null,
                    'party_street'       => $party['party_street'] ?? null,
                    'party_city'         => $party['party_city'] ?? 'UNKNOWN',
                    'party_region'       => $party['party_region'] ?? 'UNKNOWN',
                    'party_country_code' => $party['party_country_code'] ?? 'UN',
                    'party_type_id'      => $partyTypeId,
                    'service_id'         => $serviceId,
                    'created_at'         => now(),
                    'updated_at'         => now(),
                ]);
            }

            // 14) PURCHASE_ORDERS + hijos
            $allPONumbers = [];

            foreach (($parsed['purchase_orders'] ?? []) as $po) {
                $poNumber = $po['purchase_order_number'] ?? null;
                if (empty($poNumber) || empty($po['raw_segment'])) {
                    continue;
                }

                $allPONumbers[] = $poNumber;

                $purchaseOrderId = DB::table('purchase_orders')->insertGetId([
                    'segment_tag'             => $po['segment_tag'] ?? 'CNI',
                    'raw_segment'             => $po['raw_segment'],
                    'purchase_order_secuence' => $po['purchase_order_secuence'] ?? null,
                    'purchase_order_number'   => $poNumber,
                    'service_id'              => $serviceId,
                    'created_at'              => now(),
                    'updated_at'              => now(),
                ]);

                // Notificación: SIEMPRE con service_id, y NO debe tumbar el job
                try {
                    Notification::create([
                        'title'          => 'Notificación de servicio',
                        'message'        => "Se procesó la orden {$poNumber} desde {$this->fileName}.",
                        'purchase_order' => $poNumber,
                        'service_id'     => $serviceId,
                        'is_read'        => false,
                    ]);
                } catch (\Throwable $e) {
                    Log::warning("No se pudo crear Notification para PO {$poNumber}. Error: {$e->getMessage()}");
                }

                // 14.1) ORDER_REFERENCES (RFF)
                foreach (($po['references'] ?? []) as $ref) {
                    $refTypeId = $this->getOrCreateReferenceTypeId($ref['reference_type_code'] ?? null);

                    DB::table('order_references')->insert([
                        'segment_tag'           => $ref['segment_tag'] ?? 'RFF',
                        'raw_segment'           => $ref['raw_segment'],
                        'order_reference_value' => $ref['order_reference_value'] ?? null,
                        'reference_type_id'     => $refTypeId,
                        'purchase_order_id'     => $purchaseOrderId,
                        'created_at'            => now(),
                        'updated_at'            => now(),
                    ]);
                }

                // 14.2) PURCHASE ORDER CONTACTS (CTA) + DETAILS (COM)
                foreach (($po['contacts'] ?? []) as $contact) {
                    $contactTypeId = $this->getOrCreateContactTypeId($contact['contact_type_code'] ?? null);

                    $poContactId = DB::table('purchase_order_contacts')->insertGetId([
                        'segment_tag'        => $contact['segment_tag'] ?? 'CTA',
                        'contact_name'       => $contact['contact_name'] ?? null,
                        'raw_segment'        => $contact['raw_segment'],
                        'purchase_order_id'  => $purchaseOrderId,
                        'contact_type_id'    => $contactTypeId,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);

                    foreach (($contact['details'] ?? []) as $detail) {
                        DB::table('purchase_order_contact_details')->insert([
                            'segment_tag'                 => $detail['segment_tag'] ?? 'COM',
                            'channel_contact'             => $detail['channel_contact'],
                            'contact_information'         => $detail['contact_information'],
                            'raw_segment'                 => $detail['raw_segment'],
                            'purchase_order_contact_id'   => $poContactId,
                            'created_at'                  => now(),
                            'updated_at'                  => now(),
                        ]);
                    }
                }

                // 14.3) PURCHASE ORDER MEASUREMENTS (CNT)
                foreach (($po['measurements'] ?? []) as $m) {
                    $globalMeasureTypeId = $this->getOrCreateGlobalMeasureTypeId($m['global_measure_type_code'] ?? null);

                    DB::table('purchase_order_measurements')->insert([
                        'segment_tag'            => $m['segment_tag'] ?? 'CNT',
                        'measure_value'          => $m['measure_value'] ?? null,
                        'measure_unit'           => $m['measure_unit'] ?? null,
                        'raw_segment'            => $m['raw_segment'],
                        'purchase_order_id'      => $purchaseOrderId,
                        'global_measure_type_id' => $globalMeasureTypeId,
                        'created_at'             => now(),
                        'updated_at'             => now(),
                    ]);
                }

                // 14.4) PURCHASE ORDER PARTIES (NAD)
                foreach (($po['parties'] ?? []) as $party) {
                    $partyTypeId = $this->getOrCreatePartyTypeId($party['party_type_code'] ?? null);

                    DB::table('purchase_order_parties')->insert([
                        'segment_tag'        => $party['segment_tag'] ?? 'NAD',
                        'raw_segment'        => $party['raw_segment'],
                        'party_name'         => $party['party_name'] ?? null,
                        'party_street'       => $party['party_street'] ?? null,
                        'party_city'         => $party['party_city'] ?? 'UNKNOWN',
                        'party_region'       => $party['party_region'] ?? 'UNKNOWN',
                        'party_country_code' => $party['party_country_code'] ?? 'UN',
                        'party_type_id'      => $partyTypeId,
                        'purchase_order_id'  => $purchaseOrderId,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                }

                // 14.5) REQUIREMENTS (TSR)
                foreach (($po['requirements'] ?? []) as $req) {
                    DB::table('purchase_order_requirements')->insert([
                        'segment_tag'                      => $req['segment_tag'] ?? 'TSR',
                        'contract_carriage_condition_code' => $req['contract_carriage_condition_code'] ?? 'UNK',
                        'po_requirements_code'             => $req['po_requirements_code'] ?? 'UNK',
                        'additional_po_requirement_code'   => $req['additional_po_requirement_code'] ?? null,
                        'transport_priority'               => $req['transport_priority'] ?? 'UNK',
                        'raw_segment'                      => $req['raw_segment'],
                        'purchase_order_id'                => $purchaseOrderId,
                        'created_at'                       => now(),
                        'updated_at'                       => now(),
                    ]);
                }

                // 14.6) DELIVERY_TERMS (TOD)
                foreach (($po['delivery_terms'] ?? []) as $tod) {
                    $catalogId = $this->getOrCreateDeliveryTermCatalogId($tod['delivery_term_catalog_code'] ?? null);

                    DB::table('delivery_terms')->insert([
                        'segment_tag'              => $tod['segment_tag'] ?? 'TOD',
                        'raw_segment'              => $tod['raw_segment'],
                        'freight_payment_code'     => $tod['freight_payment_code'] ?? null,
                        'delivery_term_function'   => $tod['delivery_term_function'] ?? null,
                        'delivery_term_catalog_id' => $catalogId,
                        'purchase_order_id'        => $purchaseOrderId,
                        'created_at'               => now(),
                        'updated_at'               => now(),
                    ]);
                }

                // 14.7) TRANSPORT_CHARGES (TCC+PRI)
                foreach (($po['transport_charges'] ?? []) as $tc) {
                    $priceQualId = $this->getOrCreatePriceQualifierId($tc['price_qualifier_code'] ?? null);

                    DB::table('transport_charges')->insert([
                        'charge_code'        => $tc['charge_code'] ?? 'UNK',
                        'rate_class_code'    => $tc['rate_class_code'] ?? 'UNK',
                        'price_amount'       => $tc['price_amount'] ?? 0,
                        'unit_price_basis'   => $tc['unit_price_basis'] ?? null,
                        'measure_unit_code'  => $tc['measure_unit_code'] ?? null,
                        'pri_segment_raw'    => $tc['pri_segment_raw'] ?? '',
                        'tcc_segment_raw'    => $tc['tcc_segment_raw'] ?? '',
                        'price_qualifier_id' => $priceQualId,
                        'purchase_order_id'  => $purchaseOrderId,
                        'created_at'         => now(),
                        'updated_at'         => now(),
                    ]);
                }

                // 14.8) PURCHASE ORDER NOTES (FTX PO-level)
                foreach (($po['notes'] ?? []) as $note) {
                    $noteTypeId = $this->getOrCreateNoteTypeId($note['note_type_code'] ?? null);

                    DB::table('purchase_order_notes')->insert([
                        'segment_tag'       => $note['segment_tag'] ?? 'FTX',
                        'raw_segment'       => $note['raw_segment'],
                        'note_text'         => $note['note_text'] ?? null,
                        'purchase_order_id' => $purchaseOrderId,
                        'note_types_id'     => $noteTypeId,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);
                }

                // 14.9) ITEMS (GID) + hijos
                foreach (($po['items'] ?? []) as $item) {
                    $purchaseOrderItemId = DB::table('purchase_order_items')->insertGetId([
                        'segment_tag'       => $item['segment_tag'] ?? 'GID',
                        'item_number'       => (int)$item['item_number'],
                        'item_count'        => (int)$item['item_count'],
                        'item_type'         => (string)$item['item_type'],
                        'raw_segment'       => $item['raw_segment'],
                        'purchase_order_id' => $purchaseOrderId,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]);

                    // PIA -> item_product_identifiers
                    foreach (($item['products'] ?? []) as $pia) {
                        $roleId = $this->getOrCreateProductIdentifierRoleId($pia['role_code'] ?? null);
                        $typeId = $this->getOrCreateProductIdentifierTypeId($pia['identifier_type_code'] ?? null);

                        DB::table('item_product_identifiers')->insert([
                            'segment_tag'                 => $pia['segment_tag'] ?? 'PIA',
                            'raw_segment'                 => $pia['raw_segment'],
                            'identifier_value'            => $pia['identifier_value'] ?? 'UNK',
                            'product_identifier_role_id'  => $roleId,
                            'product_identifier_type_id'  => $typeId,
                            'purchase_order_item_id'      => $purchaseOrderItemId,
                            'created_at'                  => now(),
                            'updated_at'                  => now(),
                        ]);
                    }

                    // PCI -> item_container_identifiers
                    foreach (($item['packages'] ?? []) as $pci) {
                        $identifierTypeId = $this->getOrCreateIdentifierTypeId($pci['identifier_type_code'] ?? null);

                        DB::table('item_container_identifiers')->insert([
                            'segment_tag'              => $pci['segment_tag'] ?? 'PCI',
                            'raw_segment'              => $pci['raw_segment'],
                            'package_identifier_value' => $pci['package_identifier_value'] ?? null,
                            'identifier_type_id'       => $identifierTypeId,
                            'purchase_order_item_id'   => $purchaseOrderItemId,
                            'created_at'               => now(),
                            'updated_at'               => now(),
                        ]);
                    }

                    // GIN -> item_unit_identifiers
                    foreach (($item['identifiers'] ?? []) as $gin) {
                        DB::table('item_unit_identifiers')->insert([
                            'segment_tag'            => $gin['segment_tag'] ?? 'GIN',
                            'unit_identifier_type'   => $gin['identifier_qualifier'] ?? 'UNK',
                            'identifier_value_from'  => $gin['identifier_value'] ?? 'UNK',
                            'identifier_value_to'    => null,
                            'raw_segment'            => $gin['raw_segment'],
                            'purchase_order_item_id' => $purchaseOrderItemId,
                            'created_at'             => now(),
                            'updated_at'             => now(),
                        ]);
                    }

                    // MEA -> item_measures
                    foreach (($item['measurements'] ?? []) as $mea) {
                        $purposeId = $this->getOrCreateMeasurementPurposeCodeId($mea['purpose_code'] ?? null);
                        $attrId    = $this->getOrCreateMeasurementAttributeCodeId($mea['attribute_code'] ?? null);

                        $unit = $mea['unit'] ?? null;
                        $unit = ($unit !== null && trim((string)$unit) !== '') ? strtoupper(trim((string)$unit)) : 'UNK';
                        $unit = substr($unit, 0, 3);

                        DB::table('item_measures')->insert([
                            'segment_tag'                   => $mea['segment_tag'] ?? 'MEA',
                            'raw_segment'                   => $mea['raw_segment'],
                            'measure_unit_code'             => $unit,
                            'measurement_value'             => $mea['value'] ?? 0,
                            'measurement_purpose_code_id'   => $purposeId,
                            'measurement_attribute_code_id' => $attrId,
                            'purchase_order_item_id'        => $purchaseOrderItemId,
                            'created_at'                    => now(),
                            'updated_at'                    => now(),
                        ]);
                    }

                    // DIM -> item_dimensions
                    foreach (($item['dimensions'] ?? []) as $dim) {
                        $dimTypeId = $this->getOrCreateDimensionTypeId($dim['dimension_type_code'] ?? null);

                        $unit = $dim['unit'] ?? null;
                        $unit = ($unit !== null && trim((string)$unit) !== '') ? strtoupper(trim((string)$unit)) : 'UNK';
                        $unit = substr($unit, 0, 3);

                        DB::table('item_dimensions')->insert([
                            'segment_tag'            => $dim['segment_tag'] ?? 'DIM',
                            'raw_segment'            => $dim['raw_segment'],
                            'dimension_unit'         => $unit,
                            'length'                 => $dim['length'] ?? 0,
                            'width'                  => $dim['width'] ?? 0,
                            'height'                 => $dim['height'] ?? 0,
                            'dimension_type_id'      => $dimTypeId,
                            'purchase_order_item_id' => $purchaseOrderItemId,
                            'created_at'             => now(),
                            'updated_at'             => now(),
                        ]);
                    }

                    // FTX (item notes) -> item_notes
                    foreach (($item['notes'] ?? []) as $note) {
                        $noteTypeId = $this->getOrCreateNoteTypeId($note['note_type_code'] ?? null);

                        DB::table('item_notes')->insert([
                            'segment_tag'            => $note['segment_tag'] ?? 'FTX',
                            'raw_segment'            => $note['raw_segment'],
                            'note_text'              => $note['note_text'] ?? null,
                            'purchase_order_item_id' => $purchaseOrderItemId,
                            'note_types_id'          => $noteTypeId,
                            'created_at'             => now(),
                            'updated_at'             => now(),
                        ]);
                    }
                }
            }

            // 15) Consolidar purchase_orders en edifact_files.purchase_order
            $allPONumbers = array_values(array_filter(array_unique($allPONumbers)));
            if (!empty($allPONumbers)) {
                DB::table('edifact_files')
                    ->where('id', $edifactFileId)
                    ->update(['purchase_order' => implode(' ', $allPONumbers)]);
            }

            DB::commit();

            Log::info("Archivo procesado OK: {$this->fileName} transmission_id={$file['transmission_id']} service_id={$serviceId} edifact_file_id={$edifactFileId}");
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("Error al procesar {$this->fileName}: {$e->getMessage()}");
            throw $e;
        }
    }

    private function defaultStatusId(): int
    {
        return (int)(DB::table('statuses')->value('id') ?? 1);
    }

    // ==========================
    // Lookups (code -> id)
    // ==========================

    private function getOrCreateDateTypeId(?string $qualifier): int
    {
        $qualifier = (int)($qualifier ?? 0);

        $id = DB::table('date_types')->where('type_qualifier', $qualifier)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('date_types')->insertGetId([
            'type_qualifier'   => $qualifier,
            'type_description' => "AUTO {$qualifier}",
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    private function getOrCreateEquipmentTypeId(?string $code): int
    {
        $code = (string)($code ?? 'UNK');

        $id = DB::table('equipment_types')->where('equipment_type_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('equipment_types')->insertGetId([
            'equipment_type_code'        => $code,
            'equipment_type_name'        => "AUTO {$code}",
            'equipment_type_description' => "AUTO generado",
            'created_at'                 => now(),
            'updated_at'                 => now(),
        ]);
    }

    private function getOrCreateReferenceTypeId(?string $code): int
    {
        $code = (string)($code ?? 'UNK');

        $id = DB::table('reference_types')->where('reference_type_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('reference_types')->insertGetId([
            'reference_type_code'        => $code,
            'reference_type_name'        => "AUTO {$code}",
            'reference_type_description' => "AUTO generado",
            'created_at'                 => now(),
            'updated_at'                 => now(),
        ]);
    }

    private function getOrCreateContactTypeId(?string $code): int
    {
        $code = (string)($code ?? 'UNK');

        $id = DB::table('contact_types')->where('type_tag', $code)->value('id');
        if ($id) return (int)$id;

        // Detectar columna real: type_description vs type_details
        $columns = DB::getSchemaBuilder()->getColumnListing('contact_types');
        $descColumn = in_array('type_description', $columns, true) ? 'type_description'
            : (in_array('type_details', $columns, true) ? 'type_details' : null);

        $payload = [
            'type_tag'    => $code,
            'created_at'  => now(),
            'updated_at'  => now(),
        ];

        if ($descColumn) {
            $payload[$descColumn] = "AUTO {$code}";
        }

        return (int)DB::table('contact_types')->insertGetId($payload);
    }

    private function getOrCreateGlobalMeasureTypeId(?string $qualifier): int
    {
        $qualifier = (string)($qualifier ?? '0');

        $id = DB::table('global_measure_types')->where('type_qualifier', $qualifier)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('global_measure_types')->insertGetId([
            'type_qualifier'   => $qualifier,
            'type_description' => "AUTO {$qualifier}",
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    private function getOrCreatePartyTypeId(?string $qualifier): int
    {
        $qualifier = trim((string)($qualifier ?? 'UNK'));

        $id = DB::table('party_types')->where('party_qualifier', $qualifier)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('party_types')->insertGetId([
            'party_qualifier'        => $qualifier,
            'party_type_name'        => "AUTO {$qualifier}",
            'party_type_description' => "AUTO generado",
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);
    }

    private function getOrCreateTransportStageId(?int $code): int
    {
        $code = (int)($code ?? 0);

        $id = DB::table('transport_stages')->where('transport_stage_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('transport_stages')->insertGetId([
            'transport_stage_code'        => $code,
            'transport_stage_name'        => "AUTO {$code}",
            'transport_stage_description' => "AUTO generado",
            'created_at'                  => now(),
            'updated_at'                  => now(),
        ]);
    }

    private function getOrCreateTransportModeId(?int $code): int
    {
        $code = (int)($code ?? 0);

        $id = DB::table('transport_modes')->where('transport_mode_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('transport_modes')->insertGetId([
            'transport_mode_code'        => $code,
            'transport_mode_name'        => "AUTO {$code}",
            'transport_mode_description' => "AUTO generado",
            'created_at'                 => now(),
            'updated_at'                 => now(),
        ]);
    }

    private function getOrCreateLocationCodeId(?int $code): int
    {
        $code = (int)($code ?? 0);

        $id = DB::table('location_codes')->where('location_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('location_codes')->insertGetId([
            'location_code'             => $code,
            'location_code_name'        => "AUTO {$code}",
            'location_code_description' => "AUTO generado",
            'created_at'                => now(),
            'updated_at'                => now(),
        ]);
    }

    private function getOrCreateDeliveryTermCatalogId(?string $termCode): int
    {
        $termCode = strtoupper(trim((string)($termCode ?? 'UNK')));

        $id = DB::table('delivery_term_catalogs')->where('term_code', $termCode)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('delivery_term_catalogs')->insertGetId([
            'term_code'        => $termCode,
            'term_name'        => "AUTO {$termCode}",
            'term_description' => "AUTO generado",
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    private function getOrCreatePriceQualifierId(?string $code): int
    {
        $code = strtoupper(trim((string)($code ?? 'UNK')));

        $id = DB::table('price_qualifiers')->where('price_qualifier_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('price_qualifiers')->insertGetId([
            'price_qualifier_code'        => $code,
            'price_qualifier_name'        => "AUTO {$code}",
            'price_qualifier_description' => "AUTO generado",
            'created_at'                  => now(),
            'updated_at'                  => now(),
        ]);
    }

    private function getOrCreateNoteTypeId(?string $code): int
    {
        $code = strtoupper(trim((string)($code ?? 'UNK')));

        $id = DB::table('note_types')->where('note_type_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('note_types')->insertGetId([
            'note_type_code'        => $code,
            'note_type_name'        => "AUTO {$code}",
            'note_type_description' => "AUTO generado",
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);
    }

    private function getOrCreateMeasurementPurposeCodeId(?string $code): int
    {
        $code = strtoupper(trim((string)($code ?? 'UNK')));

        $id = DB::table('measurement_purpose_codes')->where('measurement_purpose_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('measurement_purpose_codes')->insertGetId([
            'measurement_purpose_code'        => $code,
            'measurement_purpose_name'        => "AUTO {$code}",
            'measurement_purpose_description' => "AUTO generado",
            'created_at'                      => now(),
            'updated_at'                      => now(),
        ]);
    }

    private function getOrCreateMeasurementAttributeCodeId(?string $code): int
    {
        $code = strtoupper(trim((string)($code ?? 'UNK')));

        $id = DB::table('measurement_attribute_codes')->where('measurement_attribute_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('measurement_attribute_codes')->insertGetId([
            'measurement_attribute_code'        => $code,
            'measurement_attribute_name'        => "AUTO {$code}",
            'measurement_attribute_description' => "AUTO generado",
            'created_at'                        => now(),
            'updated_at'                        => now(),
        ]);
    }

    private function getOrCreateDimensionTypeId(?string $code): int
    {
        $codeInt = (int)($code ?? 0);

        $id = DB::table('dimension_types')->where('dimension_type_code', $codeInt)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('dimension_types')->insertGetId([
            'dimension_type_code'        => $codeInt,
            'dimension_type_name'        => "AUTO {$codeInt}",
            'dimension_type_description' => "AUTO generado",
            'created_at'                 => now(),
            'updated_at'                 => now(),
        ]);
    }

    private function getOrCreateIdentifierTypeId(?string $code): int
    {
        $code = trim((string)($code ?? 'UNK'));

        $id = DB::table('identifier_types')->where('identifier_type_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('identifier_types')->insertGetId([
            'identifier_type_code'        => $code,
            'identifier_type_name'        => "AUTO {$code}",
            'identifier_type_description' => "AUTO generado",
            'created_at'                  => now(),
            'updated_at'                  => now(),
        ]);
    }

    private function getOrCreateProductIdentifierRoleId(?string $code): int
    {
        $codeInt = (int)($code ?? 0);

        $id = DB::table('product_identifier_roles')->where('role_code', $codeInt)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('product_identifier_roles')->insertGetId([
            'role_code'        => $codeInt,
            'role_name'        => "AUTO {$codeInt}",
            'role_description' => "AUTO generado",
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);
    }

    private function getOrCreateProductIdentifierTypeId(?string $code): ?int
    {
        if ($code === null || trim($code) === '') return null;

        $code = strtoupper(trim($code));

        $id = DB::table('product_identifier_types')->where('identifier_type_code', $code)->value('id');
        if ($id) return (int)$id;

        return (int)DB::table('product_identifier_types')->insertGetId([
            'identifier_type_code'        => $code,
            'identifier_type_name'        => "AUTO {$code}",
            'identifier_type_description' => "AUTO generado",
            'created_at'                  => now(),
            'updated_at'                  => now(),
        ]);
    }
}
