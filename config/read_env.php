<?php

$envPath = __DIR__ . '/../.env';

if (!file_exists($envPath)) {
    throw new Exception('Env file not found!', 500);
}

$envStrings = file($envPath);
foreach ($envStrings as $envString) {
    putenv($envString);
}
