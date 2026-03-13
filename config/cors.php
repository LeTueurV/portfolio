<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'],

    'allowed_origins' => [
        'http://localhost:8080',      // Frontend local Vue dev
        'http://localhost:3000',      // Frontend local React/Next
        'http://localhost:8000',      // Laravel local
        'http://localhost',           // Localhost sans port
        'https://portfolio-mlb3.onrender.com',  // Production Render
        'file://',                    // Local file tests
    ],

    'allowed_origins_patterns' => [
        '/localhost.*/',              // Any localhost variant
        '/.*(localhost|127\.0\.0\.1).*/i',  // IPv4 loopback
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Content-Length', 'Content-Type', 'Authorization'],

    'max_age' => 86400,

    'supports_credentials' => false,

];
