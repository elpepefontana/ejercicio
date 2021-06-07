<?php

namespace App\Controllers\Group;

use App\Controllers\AbstractAction;
use App\Models\Services\JsonDataService;
use System\Factories\Factory;
use System\Core\AbstractService;

class SearchAction extends AbstractAction
{
    public function __construct($model, $view, $session, $data, $isAjax)
    {
        parent::__construct($model, $view, $session, $data, $isAjax);
    }

    public function execute()
    {
        $result = $this->model->findAll($this->data);
        
        if (!$this->ajax) {
            return $result;
        }
        
        $this->callJsonDataService(
            new JsonDataService(
                new Factory(), 
                $this->session, 
                array()
            ),
            $result
        ); 
    }
    
    private function callJsonDataService(AbstractService $service, $data) 
    {
        $service->toJsonResponse($data);
    }
  
}