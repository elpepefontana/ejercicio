<?php

namespace App\Models;


class AdminHomeModel extends \System\Core\AbstractModel {
    
    protected $session;
    
    public function __construct($entity, $mapper, $session, $queryBuilder) {
        parent::__construct($entity, $mapper, $session, $queryBuilder);
    }
    
}
