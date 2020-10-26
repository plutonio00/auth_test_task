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
        'pass' => env('DB_PASS'),
        'port' => env('DB_PORT'),
    ],
    'js_files' => [
        'Registration' => [
            $basePath . 'public/js/form_handler.js'
        ],
        'Login' => [
            $basePath . 'public/js/form_handler.js'
        ],
        'Index' => [],
    ],
    'template_dir' => $basePath . 'templates/',
    'upload_dir' => $basePath . 'public/images/',
];