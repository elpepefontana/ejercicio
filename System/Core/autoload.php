<?php

spl_autoload_register(function($className)
{
    $file = ROOT . HOME . '/' . $file = str_replace('\\', '/', $className) . '.php';
    
    if (file_exists($file) && $file !== 'PDO') {
        require_once $file;
    } else {
        echo "AutoLoad: NO existe: $file<br>";
    }

});