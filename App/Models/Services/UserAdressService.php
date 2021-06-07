<?php

namespace App\Models\Services;

use System\Core\AbstractService;
use System\Factories\FactoryInterface;
use System\Core\Session;

class UserAdressService extends AbstractService
{
    
    public function __construct(FactoryInterface $factory, Session $session, array $models)
    {
        parent::__construct($factory, $session);
        $this->models = $models;
        
        $this->setModelsToUse();
    }
    
    public function getAdressData($id_user)
    {
        $person = $this->executeAction('person', 'searchByIdUsers', $id_user)['Records'][0];
        
        $id_person = $person['id'];
        
        $result = $this->executeAction(
            'adress', 
            'searchAdress', 
            array(
                'object' => 'person', 
                'id_object' => $id_person
            )
        );
        
        return $result['Result'] === 'OK' ? $result['Records'] : '';
    }
    
}
