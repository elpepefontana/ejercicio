<?php
namespace App\Controllers\Group;

use App\Controllers\AbstractAction;
use System\Core\AbstractController;

defined('BASEPATH') or exit('No se permite acceso directo');

class GroupController extends AbstractController
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
