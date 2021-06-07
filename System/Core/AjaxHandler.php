<?php

namespace System\Core;

class AjaxHandler
{
    use \System\Traits\UtilitiesTrait;
    
    private $object;
    private $method;
    private $factory;
    
    public function __construct(string $object, string $method, $data)
    {
        $this->object = $object;
        $this->method = $method;
        
        //$this->debug($data, 'AjaxHandler class');
        
        $this->factory = new \System\Factories\Factory();
        
        $formater = new \System\Formater\DataFormater('Vector', $data);
        $this->data = $formater->format();
    }
    
    public function execute()
    {
        if (empty($this->object) || empty($this->method)) {
            return;
        }
        
            $this->factory->setConfig('model', $this->object, '', 'model', '', new Session());
        $model = $this->factory->create();
        
        $result = $model->{$this->method}($this->data);
        
        if ($result['Result'] != 'OK' || $result['TotalRecordCount'] === 0) {
            return;
        }
        
        return $this->toJTableJson($result);
    }
}
