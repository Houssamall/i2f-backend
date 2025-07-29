<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'], // Specify paths for CORS
    'allowed_methods' => ['*'], // Allow all methods
    'allowed_origins' => ['http://localhost:3000'], // Your React app URL
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['Content-Type', 'X-Requested-With', 'Authorization', 'Accept'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true, // Enable this if you use cookies for authentication
];
