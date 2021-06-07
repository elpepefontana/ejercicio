<?php 

namespace App\Models;

use System\Core\AbstractModel;

defined('BASEPATH') or exit('No se permite acceso directo');

class UsersModel extends AbstractModel
{

    protected $session;
    
    public function __construct($entity, $mapper, $session, $queryBuilder) 
    {
        parent::__construct($entity, $mapper, $session, $queryBuilder);
    }
    
    public function findAll($params)
    {
        return $this->mapper->findAll($params);
    }
    
    public function findById($params)
    {
        return $this->mapper->findById($params['id']);
    }
    
    public function options()
    {
        return $this->mapper->searchOptions();
    }
    
    public function create($params)
    {
        return $this->mapper->new($params);
    }
    
    public function erase($params)
    {
        return $this->mapper->delete($params);
    }
    
    public function change($params)
    {
        return $this->mapper->modify($params);
    }

}