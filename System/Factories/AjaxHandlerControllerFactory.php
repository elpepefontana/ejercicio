<?php

namespace System\Factories;

use System\Core\AbstractController as AbstractController;
use System\Factories\FactoryInterface;

class AjaxHandlerControllerFactory
{
    use \System\Traits\UtilitiesTrait;
   
    private $object;
    private $action;
    private $type;
    private $session;
    
    public $data;
    
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->data = array();
    }
    
    public function create($object, $action, $type, $data, $session): object
    {
        //$this->debug($data, 'AjaxFactory data');
        
        $this->object = $object;
        $this->action = $action;
        $this->type = $type;
        $this->data = $data;
        $this->session = $session;
        
        //$this->debug($this->data, 'AjaxFactory data');
        
        $class = "\\App\\Controllers\\AjaxHandler\\AjaxHandlerController";
        $command = "\\App\\Controllers\\AjaxHandler\\ListAction";
        
        $this->factory->setConfig('model', $this->data['object'], '', $this->type, $this->data, $this->session);
        $model = $this->factory->create();
        
        $this->factory->setConfig('view', 'Null', '', '', '', $this->session);
        $view = $this->factory->create();
        
        return new $class(new $command($model, $view, $session, $this->data));
    }
    
    public function addData($key, $value)
    {
        $this->data[$key] = $value;
    }
    
}
