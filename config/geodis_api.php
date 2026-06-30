<?php

return [
    'enforce_https' => env('GEODIS_API_ENFORCE_HTTPS'),
    'token_ttl' => (int) env('GEODIS_API_TOKEN_TTL', 3600),
    'client_name' => env('GEODIS_API_CLIENT_NAME', 'GEODIS API'),
    'scope' => env('GEODIS_API_SCOPE', 'expedientes.read'),
];
