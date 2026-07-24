<?php

return [
    'enforce_https' => env('GEODIS_API_ENFORCE_HTTPS'),
    'token_ttl' => (int) env('GEODIS_API_TOKEN_TTL', 3600),
    'client_name' => env('GEODIS_API_CLIENT_NAME', 'GEODIS API'),
    'scope' => env('GEODIS_API_SCOPE', 'expedientes.read'),
    'rate_limit_per_minute' => (int) env('GEODIS_API_RATE_LIMIT', 60),
    'audit_log' => filter_var(env('GEODIS_API_AUDIT_LOG', true), FILTER_VALIDATE_BOOL),
    'trusted_proxies' => env('GEODIS_TRUSTED_PROXIES'),
];
