<?php

ini_set('log_errors', 'On');
ini_set('error_log', '../log/php_errors.log');

require_once ('../env.php');
$configuration = require('../config/config.php');
require_once('../src/core/autoload.php');

session_start();

try {
    $app = app\core\Application::instance();
    $app->setConfig($configuration);
    $app->run();
}
catch (app\core\exception\ApplicationException $e){
    echo "Inner app exception ".$e->getCode()." ".$e->getMessage();
}
catch (Exception $e){
    $msg = $e->getMessage() . PHP_EOL;
    $msg .= "<pre>" . $e->getTraceAsString() . "</pre>";
    echo $msg;
}

