<?php

return [
    // Separadores EDIFACT por defecto (UNOA)
    'separators' => [
        'segment' => "'",
        'data'    => '+',
        'comp'    => ':',
        'release' => '?',
    ],

    // Identificadores UNB (ajústalos a lo acordado con GEODIS)
    'unb' => [
        'syntax_id'   => 'UNOA',
        'syntax_ver'  => '1',
        'sender_id'   => env('EDI_SENDER_ID', 'TRNSTECOL'),
        'receiver_id' => env('EDI_RECEIVER_ID', 'GEODIS'),
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
];
