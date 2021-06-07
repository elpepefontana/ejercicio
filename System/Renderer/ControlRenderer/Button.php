<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;

class Button extends AbstractControl implements ControlInterface
{
    private $onclick;
    
    public function __construct($typeData, $data)
    {
        parent::__construct($typeData, $data);
        $this->setData();
    }
    
    private function setData()
    {
        foreach ($this->typeData as $key => $value) {
            if (!property_exists(__CLASS__, $key)) {
                continue;
            }
            $this->$key = $value;
        }
    }
    
    public function render(): string
    {
        $buttonName  = !empty($this->name) ? "id=\"{$this->name}\" name=\"{$this->name}\"" : '';
        $buttonClass = !empty($this->css_class) ? "class=\"button {$this->css_class}\"" : 'class="button"';
        $onClick = !empty($this->onclick) ? "onclick=\"{$this->onclick}\"" : "";
        return "<button {$buttonName} {$buttonClass} {$this->attributes} {$onClick}>{$this->content}</button>";
    }
    
}
