<?php

namespace App\Controllers\ErrorPage;

use App\Controllers\AbstractAction;

defined('BASEPATH') or exit('No se permite acceso directo');

class ErrorPageController extends \System\Core\AbstractController 
{
    public function __construct(AbstractAction $action) 
    {
        parent::__construct();
        $this->action = $action;
    }
    
    public function exec($params)
    {
        //$this->verifySession();
        
        $this->action->setData($params);
        $this->action->execute();
    }

    // VIEW RENDER FUNCTION MARKER -- DONT ERASE -- MARCADOR DE FUNCIONES DE RENDERIZADO DE VISTAS -- NO BORRAR //
    
}
