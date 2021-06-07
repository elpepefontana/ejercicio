<?php

namespace System\Factories;

use System\Core\AbstractController as AbstractController;
use System\Factories\FactoryInterface;

class AdminControllerFactory implements ControllerFactoryInterface
{
    use \System\Traits\UtilitiesTrait;
   
    private $object;
    private $action;
    private $type;
    private $session;
    
    public $data;
    
    public function __construct(FactoryInterface $factory, $data)
    {
        $this->factory = $factory;
        $this->data = $data;
    }
    
    public function create($object, $action, $type, $data, $session): ?AbstractController
    {
        $this->object = $object;
        $this->action = $action;
        $this->type = $type;
        $this->data = $data;
        $this->session = $session;
        
        $class = $this->setControllerClass();
        $command = $this->setActionClass();
        
        $this->factory->setConfig('model', $this->data['target'], '', $this->type, $this->data, $this->session);
        $model = $this->factory->create();
        
        $this->factory->setConfig('view', $this->data['target'], '', '', $this->data, $this->session);
        $view = $this->factory->create();
        
        return new $class(new $command($model, $view, $session, $this->data));
    }
    
    private function setControllerClass(): ?string
    {
        return "\\App\\Controllers\\Admin\\AdminController";
    }
    
    private function setActionClass()
    {
        return "\\App\\Controllers\\Admin\\{$this->toCamelCase($this->action)}Action";
    }
    
    
    
    
}
