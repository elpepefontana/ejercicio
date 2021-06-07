<?php

namespace System\Core;

use System\Factories\FactoryInterface;
use System\Core\Session;

class AbstractService
{
    
    use \System\Traits\UtilitiesTrait;
    
    private $session;
    protected $models = [];
    
    public function __construct(FactoryInterface $factory, Session $session)
    {
        $this->factory = $factory;
        $this->session = $session;
    }
    
    public function setModelsToUse(): void
    {
        if (empty($this->models)) {
            return;
        }
        
        foreach($this->models as $name => $class) {
            $this->setModel($name, $class);
        }
    }
    
    public function setModel(string $name, string $class)
    {
        if (property_exists($this, $name)) {
            return; 
        }
        
        $this->factory->setConfig(
            'model',
            $class,
            '',
            'model',
            '',
            $this->session
        );

        $this->{$name} = $this->factory->create();
    }
    
    public function executeAction(string $model, string $action, $params)
    {
        $modelPath = MODEL_PATH . $this->toCamelCase($model, true);
        
        if (!property_exists($this, $model) && !method_exists($modelPath, $action)) {
            return;
        }
        
        $result = null;
        
        if (empty($params)) {
            $result = $this->{$model}->{$action}(array());
        } else {
            $result = $this->{$model}->{$action}($params);
        }
        
        if (empty($result)) {
            return;
        }
        
        return $result;
    }
    
}
