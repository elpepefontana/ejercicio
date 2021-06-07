<?php

namespace App\Models\Services;

use System\Core\AbstractService;
use System\Factories\FactoryInterface;
use System\Core\Session;

class JsonDataService  extends AbstractService
{
    
    public function __construct(FactoryInterface $factory, Session $session, array $models)
    {
        parent::__construct($factory, $session);
        $this->models = $models;
        
        $this->setModelsToUse();
    }
    
    public function toJsonResponse($data)
    {
        echo json_encode($data);
    }
}
