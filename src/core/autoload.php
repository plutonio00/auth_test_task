<?php
spl_autoload_register('autoloader');

function autoloader($class){

    $classData = explode('\\', $class);
    array_shift($classData);

    $path = __DIR__ . '/..';


    foreach($classData as $item){
        $path .= '/' . $item;
    }

    $path .= '.php';

    if(file_exists($path)){
        require_once($path);
    }
    else{
        throw new Exception('Class ' . $class . ' wasn\'t found in '.$path, 404);
    }
}
