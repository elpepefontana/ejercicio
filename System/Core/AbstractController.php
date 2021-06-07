<?php

namespace System\Core;

use System\Traits\UtilitiesTrait as UtilitiesTrait;

defined('BASEPATH') or exit('No se permite acceso directo');

abstract class AbstractController 
{
    use UtilitiesTrait;
    
    protected $acl;
    protected $logger;
    protected $session; 
    protected $cookie;
    
    protected $action;
    protected $view;
    protected $model;
    
    protected $factory;
    
    protected $data;
    
    public function __construct()
    {
        $this->factory = new \System\Factories\Factory();
        
        $this->cookie = new \System\Core\Cookie(COOKIE_DATA);
        
        $qb = new QueryBuilder();
        $this->session = new Session();
        
        $this->acl = new \System\Core\Acl($this->session, $qb);
        $this->logger = new \System\Core\Logger($this->session, $qb);
        
        $this->setSessionStart();
    }   
    
    abstract public function exec($params);
                
    public function setSessionStart() 
    {
        if (!$this->session->isStarted()) {
            $this->session->init();
        }
    }
    
    public function logOut() 
    {
        $this->session->close();
        header("location: " . HOME . "/Login");  
        die();
    } 

    public function verifySession() 
    {
        if (!LOGIN_SWITCH) {
            return;
        }
        
        $this->setSessionStart();
        
        switch ($this->session->getStatus()) {
            case 0:
            case 1:
                $this->logOut();
                break;
            case 2:
                if (empty($this->session->get('user'))) {
                    $this->logOut();
                    return;
                }
                $life = time() - $this->session->get('start');
                $life > TIMEOUT ? $this->logOut() : $this->session->add('start', time());
                break;
        }
    }
    
    public function ajaxHandler($params): void
    {
        $ajaxHandler = new AjaxHandler(
            $params['object'], 
            $params['method'], 
            $params['data']
        );
        echo $ajaxHandler->execute();
    }
    
    public function getWebContent(string$className, string $lang)
    {
        return $this->model->getWebContent($className, $lang);
    }
    
    public function addViewData(string $name, $value)
    {
        $this->view->addData($name, $value);
    }
    
    public function renderView($name, $params, $type)
    {
        $this->view->render($name, $params, $type);
    }
    
    public function sessionGet($key)
    {
        return $this->session->get($key);
    }
    
    public function sessionAdd($key, $value)
    {
        $this->session->add($key, $value);
    }
    
    public function sessionInit()
    {
        $this->session->init();
    }
    
    public function isAdmin()
    {
        return $this->acl->isAdmin($this->session->get('user_id'));
    }
    
    public function validateAccess($obj)
    {
        return $this->acl->genAccess($obj, '2');
    }
}