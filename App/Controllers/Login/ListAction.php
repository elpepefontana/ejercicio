<?php

namespace App\Controllers\Login;

use App\Controllers\AbstractAction;
use System\Core\AbstractController;

class ListAction extends AbstractAction
{
    public function __construct($model, $view, $session, $data)
    {
        parent::__construct($model, $view, $session, $data);
    }
    
    public function execute()
    {
        $this->view->render('login', '', 'Obj');
    }
  
}
