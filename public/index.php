<?php
$configuration = require_once('../config/config.php');
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
    $msg = $e->getMessage()."<br>";
    $msg .= "<pre>" . $e->getTraceAsString() . "</pre>";
    echo $msg;
}

