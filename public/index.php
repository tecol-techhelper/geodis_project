<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Prevent PHP warnings/notices from being printed into HTTP responses.
// Livewire expects JSON in its XHR endpoints and raw HTML warnings break it.
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Capture PHP runtime warnings/notices that could break JSON endpoints like Livewire.
set_error_handler(static function (int $severity, string $message, string $file, int $line): bool {
    $levels = [
        E_ERROR => 'E_ERROR',
        E_WARNING => 'E_WARNING',
        E_PARSE => 'E_PARSE',
        E_NOTICE => 'E_NOTICE',
        E_CORE_ERROR => 'E_CORE_ERROR',
        E_CORE_WARNING => 'E_CORE_WARNING',
        E_COMPILE_ERROR => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING => 'E_COMPILE_WARNING',
        E_USER_ERROR => 'E_USER_ERROR',
        E_USER_WARNING => 'E_USER_WARNING',
        E_USER_NOTICE => 'E_USER_NOTICE',
        E_STRICT => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED => 'E_DEPRECATED',
        E_USER_DEPRECATED => 'E_USER_DEPRECATED',
    ];

    $level = $levels[$severity] ?? ('E_' . $severity);
    $logPath = __DIR__ . '/../storage/logs/php_runtime_errors.log';
    $lineData = sprintf("[%s] %s: %s in %s:%d\n", date('Y-m-d H:i:s'), $level, $message, $file, $line);
    @file_put_contents($logPath, $lineData, FILE_APPEND);

    // Mark as handled to avoid leaking HTML warnings into responses.
    return true;
});

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
