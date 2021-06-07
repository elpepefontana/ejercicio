<?php 

namespace App\Models;

class LogModel extends \System\Core\AbstractModel {
    
    protected $session;
    
    public function __construct($entity, $mapper, $session, $queryBuilder) {
        parent::__construct($entity, $mapper, $session, $queryBuilder);
    }


    // LOG MODEL FUNCTIONS

    public function searchLog($params){
        $this->mapper->searchLog($params);
    }

    public function searchActiveLog($params){
        $this->mapper->searcActiveLog($params);
    }

    public function createLog($params){
        $data = $this->entity->validate('\App\Models\Entities\LogEntity', $params);
        $this->mapper->createLog($data);
    }

    public function changeLog($params){
        $data = $this->entity->validate('\App\Models\Entities\LogEntity',$params);
        $this->mapper->changeLog($data);
    }

    public function eraseLog($params){
        $this->mapper->eraseLog($params);
    }



        // LOG SPECIAL COMBO FUNCTIONS

    public function comboFolderOptions(){
        $this->toJTableJson($this->genFolderCombo(HOME . '/app/views/'));
    }

    public function comboSearchLog($params){
        $this->mapper->comboSearchLog('log', $params);
    }

}
