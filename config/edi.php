<?php

return [
    // Separadores EDIFACT por defecto (UNOA)
    'separators' => [
        'segment' => "'",
        'data'    => '+',
        'comp'    => ':',
        'release' => '?',
    ],

    // Identificadores UNB (ajÃºstalos a lo acordado con GEODIS)
    'unb' => [
        'syntax_id'   => 'UNOA',
        'syntax_ver'  => '1',
        'sender_id'   => env('EDI_SENDER_ID', 'TRANSTECOL'),
        'receiver_id' => env('EDI_RECEIVER_ID', 'GEOSCOT'),
        'app_ref'     => env('EDI_APP_REF', 'IFTSTA'),
    ],

    // UNH
    'unh' => [
        'message_type'    => 'IFTSTA',
        'message_version' => env('EDI_MSG_VER', 'D'),
        'message_release' => env('EDI_MSG_REL', '96A'),
        'controlling_agency' => env('EDI_MSG_AGENCY', 'UN'),
    ],

    // Para nombres de archivo
    'file_naming' => [
        'prefix' => 'IFTSTA',
        'extension' => 'edi',
    ],

    // IFTSTA
    'iftsta' => [
        // Primer componente del STS+X+Y
        'sts_type' => env('EDI_IFTSTA_STS_TYPE', '1'),
    ],

    // Segmentos a omitir en generaciÃ³n (por tag EDIFACT)
    // Ej: ['TOD','TCC','PRI']
    'omit_segments' => array_filter(array_map('trim', explode(',', env('EDI_OMIT_SEGMENTS', '')))),
];
