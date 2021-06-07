<?php

define('BASEPATH', true);

include_once '../System/config.php';
include_once CORE_PATH . 'autoload.php';

error_reporting(ERROR_REPORTING_LEVEL);

$application = new \System\Core\Application(
    new \System\Factories\Factory(),
    new \System\Core\Router(),
    new \System\Core\Session()
);

$application->run();