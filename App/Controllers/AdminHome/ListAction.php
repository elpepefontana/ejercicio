<?php

namespace App\Controllers\AdminHome;

use App\Controllers\AbstractAction;
use System\Core\QueryBuilder;

class ListAction extends AbstractAction
{
    public function __construct($model, $view, $session, $data)
    {
        parent::__construct($model, $view, $session, $data);
    }
    
    public function execute()
    {
        $this->view->setData($this->data);
        $this->view->render('adminhome', '', 'Obj');
    }
  
}
