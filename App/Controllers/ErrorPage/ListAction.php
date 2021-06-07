<?php

namespace App\Controllers\ErrorPage;

use App\Controllers\AbstractAction;

class ListAction extends AbstractAction
{
    public function __construct($model, $view, $session, $data)
    {
        parent::__construct($model, $view, $session, $data);
    }
    
    public function execute()
    {
        $this->view->render('error', '', 'Obj');
    }
  
}
