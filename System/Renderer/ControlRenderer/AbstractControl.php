<?php

namespace System\Renderer\ControlRenderer;

abstract class AbstractControl
{
    protected $name;
    protected $css_class;
    protected $attributes;
    protected $content;
    
    protected $typeData;
    protected $data;
    
    protected $template;
    
    public function __construct(array $typeData, array $data)
    {
        $this->typeData = $typeData;
        $this->data = $data;
        $this->template = new \System\Core\Template(new \System\Core\Session(), CONTROLS_PATH);
    }
    
    abstract public function setData();
    
    public function addData(
        string $adicionalDataName, 
        string $adicionalDataValue
    ): void {
        $this->typeData[$adicionalDataName] = $adicionalDataValue;
    }
    
    public function render($name, $params): string
    {   
        $this->template->load($name, $params);
    }
}
