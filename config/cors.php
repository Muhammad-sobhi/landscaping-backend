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

    'paths' => ['api/*', 'sanctum/csrf-cookie','login', 'api/login'],

    'allowed_methods' => ['*'],

   'allowed_origins' => [
    'http://localhost:3000',
    'http://localhost:5173',
    'https://additional-georgina-thattreeguy-c7fb11d9.koyeb.app',
    'https://decent-leese-thattreeguy-4b352b8b.koyeb.app', // Added your Netlify domain
],

'allowed_headers' => ['*'],

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => true,

];
