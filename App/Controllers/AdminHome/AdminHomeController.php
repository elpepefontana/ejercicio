<?php
namespace App\Controllers\AdminHome;

use App\Controllers\AbstractAction;

defined('BASEPATH') or exit('No se permite acceso directo');

class AdminHomeController extends \System\Core\AbstractController
{
    public function __construct(AbstractAction $action) {
        parent::__construct();
        $this->action = $action;
    }
    
    public function exec($params)
    {
        $this->action->execute();
    }
    
}
