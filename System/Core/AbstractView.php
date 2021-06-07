<?php

namespace System\Core;

defined('BASEPATH') or exit('No se permite acceso directo');

abstract class AbstractView 
{
    use \System\Traits\UtilitiesTrait;
    
    protected $template;  
    protected $session;
    protected $dataFormat;
    protected $renderer;
    public $data = [];

    public function __construct($session)
    {
        $this->session = $session;
        $this->template = new Template($this->session);
        //$this->renderer = new \System\Renderer\ControlRenderer\ControlRenderer();
    }
    
    public function render($name, $params): void
    {   
        $this->verifyDataExistance($params);
        
        if (is_array($name)) {
            $this->iterateThroughViewsAndRender($name);
            return;
        }
        
        echo $this->template->load($name, $this->data); 
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function addData(string $name, $data)
    {
        if (empty($name) && !empty($data)) {
            $this->iterate($data);
            return;
        }
        $this->data[$name] = $data;
    }
    
    private function iterate(array $data)
    {
        foreach ($data as $key => $value) {
            if (isset($this->data[$key])) {
                continue;
            }
            
            $this->data[$key] = $value;
        }
    }
    
    private function verifyDataExistance($data)
    {
        if (empty($this->data)) {
            $this->setData($data);
        }
    }
    
    public function html(string $str): string 
    {
        return htmlspecialchars($str);
    }
    
    private function iterateThroughViewsAndRender(array $views, array $params): void
    {
        $view = '';
        foreach ($views as $name) {
            $view .= $this->template->load($name, $params[$name]);
        }
        
        echo $view;
    }
    
    
}