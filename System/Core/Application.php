<?php

namespace System\Core;

use System\Factories\Factory;
use System\Core\Router;
use System\Core\Session;

class Application
{
    
    use \System\Traits\UtilitiesTrait;
    
    public $router;
    public $session;
    
    private $routedController;
    private $routedSubFolder;
    private $routedAction;
    private $routedParam;
    private $isAjax;
    
    private $controller;
    
    public function __construct(Factory $factory, Router $router, Session $session)
    {
        $this->factory = $factory;
        $this->router = $router;
        $this->session = $session;
        
        if ($router->getController() === 'index.php' || empty($router->getController())) {
            $this->routedController = 'AdminHome';
        } else {
            $this->routedController = $router->getController();
        }
        
        $this->routedSubFolder = $router->getSubFolder();

        $this->routedAction = !empty($router->getAction()) ? $router->getAction() : 'List';
        
        $this->routedParam = $router->getParam();
        
        $this->isAjax = $router->getIsAjax();
        
        //$this->debug($this->isAjax, 'PARAMS');
    }
    
    private function setController(): void
    {
        $this->factory->setConfig(
            'controller', 
            $this->routedController, 
            $this->routedAction,
            $this->routedSubFolder,
            $this->routedParam,
            $this->session,
            $this->isAjax
        );
        
        $this->controller = $this->factory->create();
    }
    
    public function run(): void
    {
        $this->setController();
        
        //$this->debug($this->routedAction, 'Action');
        
        $this->controller->exec($this->routedParam);
    }
    
    private function validateAdminFieldsData()
    {
        if ($this->routedController !== 'Admin') {
            return;
        }
        
        if (!isset($this->routedParam['target']) || !isset($this->routedParam['label'])) {
            return;
        }
        
        $this->factory->addConfig('target', $this->routedParam['target']);
        $this->factory->addConfig('label', $this->routedParam['label']);
    }
    
}
