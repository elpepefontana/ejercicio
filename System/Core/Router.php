<?php

namespace System\Core;

defined('BASEPATH') or exit('No se permite acceso directo');

class Router 
{
    use \System\Traits\UtilitiesTrait;
    
    private $uri;
    private $controller;
    private $subFolder = '';
    private $action;
    private $param;
    private $isAjax;
    
    public function __construct()
    {
        $this->setUri();
        $this->setController();
        $this->setAction();
        $this->setParam();
        $this->isAjaxCall();
    }
    
    private function checkHost()
    {
        if (strpos(HOST, 'localhost')) {
            
        }
    }
    
    public function setUri()
    {
        $this->uri = explode('/', URI); 
    }
    
    public function setController()
    {
        $this->controller = $this->uri[2] === '' ? 'AdminHome' : $this->uri[2];
        
        $this->extractControllerFromFormula();
    }
    
    public function extractControllerFromFormula()
    {
        if (strpos($this->controller, ':') === false) {
            return;
        }
        
        $parts = explode(':', $this->controller);
        
        $this->controller = array_pop($parts);
        
        $this->iterateSubFolder($parts);
    }
    
    private function iterateSubFolder(array $parts)
    {
        $out = '';
        foreach ($parts as $part) {
            $out .= !empty($out) ? '/' . $this->toCamelCase($part, true) : $this->toCamelCase($part, true);
        }
        
        $this->subFolder = $out;
    }
    
    public function setAction()
    {
        $this->action = !empty($this->uri[3]) ? $this->uri[3] : 'List';
    }
    
    public function setParam()
    {
        $get = !empty($this->uri[4]) ? $this->genParamHolder($this->uri[4]) : [];
        
        $this->param = !empty($_POST) ? $_POST : [];
        
        if (strtolower($this->getController()) === 'ajaxhandler') {
            //$this->debug($this->param, 'Router post data');
        }
        
        $this->param = !empty($this->param) ? $this->param + $get : $get;
        
        if(!empty($_FILES)) {
            $this->param['files'] = $_FILES;
        }
    }
    
    private function isAjaxCall()
    {
        if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
            $this->isAjax = false;
        }
        
        $this->isAjax = true;
    }
    
    private function validateDataType($param)
    {
        
    }
    
    public function validateFileSent($fileName)
    {
        if (isset($fileName)) {
            return $_FILES;
        }
    }
    
    public function genParamHolder($str)
    {
        if (DATA_HOLDER === 'array') {
            return $this->genParamArray($str);
        } else {
            return $this->genParamObject($str);
        }
    }
    
    public function genParamArray($str) 
    {
        $array = explode( '&', $str );
        if (!is_array($array)) {
            return array();
        }
        
        foreach ($array as $x) {
            list($k, $v) = explode('=', $x);
            $out[$k] = $v;  
        }
        
        return $out;
    }
    
    public function genParamObject($str) 
    {
        $array = explode('&', $str);
        
        $obj = new \stdClass();
        if (!is_array($array)) {
            return $obj;
        }
        
        foreach ($array as $x) {
            list($key, $value) = explode('=', $x);
            $obj->$key = $value;  
        }
        
        return $obj;
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    
    public function getController()
    {
        return $this->controller;
    }
    
    public function getAction()
    {
        return $this->action;
    }
    
    public function getSubFolder()
    {
        return $this->subFolder;
    }
    
    public function getParam()
    {
        return $this->param;
    }
    
    public function getIsAjax()
    {
        return $this->isAjax;
    }
}