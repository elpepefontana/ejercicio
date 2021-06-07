<?php

namespace App\Views;

defined('BASEPATH') or exit('No se permite acceso directo');

class NullView extends \System\Core\AbstractView
{
    public function __construct($session)
    {
       parent::__construct($session);
    }
    
    public function render($name, $params): void
    {   
        parent::render($name, $params);
    }
}