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
        'host' => env('MYSQL_HOST'),
        'db_name' => env('MYSQL_DATABASE'),
        'user' => env('MYSQL_USERNAME'),
        'password' => env('MYSQL_PASSWORD'),
        'port' => env('MYSQL_PORT'),
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