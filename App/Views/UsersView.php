<?php

namespace App\Views;

defined('BASEPATH') or exit('No se permite acceso directo');

class UsersView extends \System\Core\AbstractView
{
    public function __construct($session)
    {
       parent::__construct($session);
    }

    public function render($name, $params, $dataFormat = 'Json'): void
    {
        $formatter = new \System\Formater\DataFormater($dataFormat, $params);
        parent::render($name, $formatter->format());
    }
}
