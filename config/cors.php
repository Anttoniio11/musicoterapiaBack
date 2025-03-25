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


    'paths' => ['api/*', 'v1/*'],  // Solo las rutas relevantes
    'allowed_methods' => ['*'],  // Permitir todos los métodos (GET, POST, PUT, DELETE, etc.)
    'allowed_origins' => ['*'], 
    'allowed_origins_patterns' => [],  // Si necesitas un patrón específico, agrégalo aquí
    'allowed_headers' => ['*'],  // Permitir todos los encabezados
    'exposed_headers' => [],  // Cabeceras que pueden ser visibles en el navegador
    'max_age' => 0,  // Puedes cambiarlo para almacenar en caché las respuestas de preflight
    'supports_credentials' => false,  // Mantener en false si no usas cookies o credenciales

];
