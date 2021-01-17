<?php

if(!function_exists('env')) {
    function env($key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        return $value;
    }
}

$basePath = __DIR__ . '/../';

return [
    'db' => [
        'host' => env('DB_HOST'),
        'name' => env('DB_NAME'),
        'user' => env('DB_USERNAME'),
        'password' => env('DB_PASSWORD'),
        'port' => env('DB_PORT'),
    ],
    'js_files' => [
        'Registration' => [
            '/js/form_handler.js'
        ],
        'Login' => [
            '/js/form_handler.js'
        ],
        'Profile' => [],
    ],
    'template_dir' => $basePath . 'templates/',
    'upload_dir' => $basePath . 'public/images/',
];