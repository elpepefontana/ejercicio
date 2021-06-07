<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;

class Footer extends AbstractControl implements ControlInterface
{
    
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
        $sectionName = $this->name !== '' ? "id=\"" .str_replace(' ', '_', $this->name) . "\"" : '';
        return "<footer {$sectionName} class=\"{$this->css_class}\" {$this->attributes}>\n{$this->value}\n</footer>\n";
    }
    
}