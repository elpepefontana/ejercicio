<?php

namespace System\Factories;

use System\Core\AbstractController;
use System\Factories\FactoryInterface;

class ControllerFactory implements ControllerFactoryInterface
{
    
    use \System\Traits\UtilitiesTrait;
   
    private $object;
    private $action;
    private $type;
    private $session;
    private $data;
    
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }
    
    public function create($object, $action, $type, $data, $session, $isAjax = false): ?AbstractController
    {
        $this->object = $object;
        $this->action = $action;
        $this->type = $type;
        $this->data = $data;
        $this->session = $session;
        
        $class = $this->setControllerClass();
        $command = $this->setActionClass();
        
        $this->factory->setConfig('model', $this->object, '', $this->type, '', $this->session);
        $model = $this->factory->create();
        
        $this->factory->setConfig('view', $this->object, '', '', '',$this->session);
        $view = $this->factory->create();
        
        return new $class(new $command($model, $view, $session, $this->data, $isAjax));
    }
    
    private function setControllerClass(): ?string
    {
        $builder = new \System\Core\PathBuilder(
            'controller',
            '\\App\Controllers\\',
            $this->object,
            $this->action,  
            $this->type,
            ''
        );
        $builder->build();
        return $builder->toClass();
    }
    
    private function setActionClass()
    {
        $builder = new \System\Core\PathBuilder(
            'Action',
            '\\App\Controllers\\',
            $this->object,
            $this->action,
            $this->type,
            ''
        );
        $builder->build();
        return $builder->toClass();
    }
    
}
