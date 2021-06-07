<?php

namespace App\Controllers\Group;

use App\Controllers\AbstractAction;

class ListAction extends AbstractAction
{
    
    public function __construct($model, $view, $session, $data, $isAjax)
    {
        parent::__construct($model, $view, $session, $data, $isAjax);
    }

    public function execute()
    {
        $this->view->render('default|group_list->main', '', 'Obj');
    }
  
}
