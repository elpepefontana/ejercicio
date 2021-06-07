<?php

namespace System\Factories;

use System\Core\AbstractModel as AbstractModel;

class ModelFactory implements ModelFactoryInterface
{
    use \System\Traits\UtilitiesTrait;
    
    private $type = 'model';
    private $factory;
    private $action;
    
    private $helper;
    private $qb;
    private $session;
    private $data;
    
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }
    
    public function create($object, $action, $type, $data, $session): ?AbstractModel
    {
        $this->object = $this->toCamelCase($object, true);
        $this->action = $action;
        $this->type = !empty($type) ? $type : $this->type;
        $this->data = $data;
        $this->session = $session;
        
        $this->helper  = new \System\Helpers\UserInputHelper();
        $this->qb      = new \System\Core\QueryBuilder();
        
        $class = $this->setRoute() . $this->object . 'Model';
        
        return new $class(
            $this->setEntity(),
            $this->setMapper(),
            $this->session,
            $this->qb
        );
    }
    
    private function setRoute()
    {
        if (strtolower($this->type) === 'codegen') {
            return '\\App\\Models\\CodeGen\\';
        } 
        return '\\App\\Models\\';
    }
    
    private function setEntity() 
    {
        $arr = explode(":", $this->object);
        $prefix = end($arr);
        
        $class = $this->setRoute();
        $class .= str_replace($prefix, "Entities", $this->object);
        $class .= "\\" . $this->toCamelCase($prefix, true) . "Entity";
        
        return new $class($this->helper);
    }
    
    private function setMapper()
    {
        $arr = explode("\\", $this->object);
        $prefix = end($arr);
        
        $class  = $this->setRoute();
        $class .= str_replace($prefix, "Mappers", $this->object);
        $class .= "\\" . $this->toCamelCase($prefix, true) . "Mapper";
        
        return new $class(
            $this->setDatabase(),
            $this->session,
            $this->qb
        );
    }
    
    private function setDatabase()  
    {
        return new \System\Storage\Database(
            $this->session, 
            $this->qb
        );
    }


}
