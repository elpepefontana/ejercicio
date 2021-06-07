<?php

namespace App\Controllers\Users;

use App\Controllers\AbstractAction;

class ListAction extends AbstractAction
{
    
    public function __construct($model, $view, $session, $data)
    {
        parent::__construct($model, $view, $session, $data);
    }

    public function execute()
    {
        $this->view->render('default|user_data->main', '', 'Obj');
    }
  
}
