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
        'http://localhost',
        'http://localhost:3000',
        'http://localhost:5173',
        'http://localhost:8000',
        'http://127.0.0.1',
        'https://portfolio-mlb3.onrender.com'

        // Production domains - à adapter
        // 'https://your-frontend.vercel.app',
        // 'https://your-frontend.netlify.app',
    ],

    'allowed_origins_patterns' => [
        '#http://localhost.*#',
        '#https://.*\.render\.com#', // Render deployments
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => ['Content-Length', 'Content-Type', 'Authorization'],

    'max_age' => 86400,

    'supports_credentials' => false,

];
