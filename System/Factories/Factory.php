<?php

namespace System\Factories;

class Factory implements FactoryInterface
{
    use \System\Traits\UtilitiesTrait;
    
    private $factory;
    public $config = null; 
    
    public function __construct()
    {
        $this->config = new \stdClass();
    }
    
    public function create(): ?object 
    {
        if ($this->config->factoryType === 'model') {
            $this->config->action = empty($this->config->subType) ? 'model' : $this->config->subType;
        }
        
        $factoryType = $this->getTypeOfFactory();
        
        $this->createFactory($factoryType);
        
        if (!is_object($this->factory)) {
            return null;
        }
        
        return $this->decideControllerFactoryKind();
    }
    
    private function decideControllerFactoryKind()
    {
        if (strtolower($this->config->factoryType) !== 'controller') {
            return $this->factoryCreate();
        }
        
        if (strtolower($this->config->object) === 'ajaxhandler') {
            return $this->AjaxHandlerFactoryCreate();
        }
        
        if (strtolower($this->config->object) === 'admin' && isset($this->config->target)) {
            return $this->adminFactoryCreate();
        }
        
        return $this->factoryCreate();
    }
    
    private function factoryCreate()
    {
        return $this->factory->create(
            $this->config->object,
            $this->config->action,
            $this->config->type,
            $this->config->data,
            $this->config->session,
            $this->config->isAjax
        );
    }
    
    private function adminFactoryCreate()
    {
        return $this->factory->create(
            $this->config->object,
            $this->config->action,
            $this->config->type,
            $this->config->data,
            $this->config->session,
            $this->config->isAjax
        );
    }
    
    private function AjaxHandlerFactoryCreate()
    {
        //$this->debug($this->config);
        
        return $this->factory->create(
            $this->config->object,
            $this->config->action,
            $this->config->type,
            $this->config->data,
            $this->config->session,
            true    
        );
    }
    
    private function getTypeOfFactory()
    {
        $factoryType = "\\System\\Factories\\" . ucfirst($this->config->factoryType) . "Factory";
        
        if (strtolower($this->config->factoryType) !== 'controller') {
            $factoryType = "\\System\\Factories\\" . ucfirst($this->config->factoryType) . "Factory";
        }
        
        if (strtolower($this->config->object) === 'ajaxhandler' || strtolower($this->config->object) === 'form') {
            $factoryType = "\\System\\Factories\\AjaxHandlerControllerFactory";
        }
        
        if (strtolower($this->config->object) === 'admin' && isset($this->config->target)) {
            $factoryType = "\\System\\Factories\\AdminControllerFactory";
        }
        
        return $factoryType;
    }
    
    private function createFactory($factoryType)
    {
        if (
            strtolower($this->config->factoryType) === 'controller' 
            && strtolower($this->config->object) === 'admin'
        ) {
            $factoryType = "\\System\\Factories\\AdminControllerFactory";
        }
        
        $this->factory = new $factoryType($this, $this->config->data);
    }
    
    public function setAdminFieldsConfig($target, $label)
    {
        if ($this->config->factoryType !== 'controller' || $this->config->object !== 'Admin') {
            return;
        }
        
        $this->config->target = $target; 
        $this->config->label = $label;
    }
    
    public function setConfig($factoryType, $object, $action, $type, $data, $session, $isAjax = false)
    {
        $this->config->factoryType = $factoryType;
        $this->config->object = $object;
        $this->config->action = $action;
        $this->config->type = $type;
        $this->config->data = $data;
        $this->config->session = $session;
        $this->config->isAjax = $isAjax;
    }
    
    public function addConfig($key, $value)
    {
        if (isset($this->config->$key)) {
            return;
        }
        
        $this->config->$key = $value;
    }
    
    public function changeConfig($key, $value)
    {
        if (!isset($this->config->$key)) {
            return;
        }
        
        $this->config->$key = $value;
    }
    
    public function removeConfig($key)
    {
        if (isset($this->config->$key)) {
            return;
        }
        
        unset($this->config->$key);
    }
    
}
