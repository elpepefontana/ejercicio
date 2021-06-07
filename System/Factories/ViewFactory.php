<?php

namespace System\Factories;

use System\Core\AbstractView;
use System\Factories\FactoryInterface;

class ViewFactory implements ViewFactoryInterface
{
    use \System\Traits\UtilitiesTrait;
    
    private $factory;
    private $object;
    private $action;
    private $type;

    private $session;
    
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }
    
    public function create($object, $action, $type, $data, $session): AbstractView
    {
        $this->object  = $this->toCamelCase($object);
        $this->action  = $action;
        $this->type    = $type;
        $this->session = $session;  
        
        $class = '\\App\\Views\\' . $this->object . 'View';
        
        if (!$this->verifyViewFileExitance($class)) {
            $class = '\\App\\Views\\NullView';
        }
        
        return new $class($this->session);
    }
    
    private function verifyViewFileExitance($class)
    {
        $file = ROOT . HOME . '/' . str_replace('\\', '/', $class) . '.php';
        return file_exists($file);
    }

}
