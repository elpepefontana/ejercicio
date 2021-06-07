<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;
use System\Core\Controls as Controls;

class Heading extends AbstractControl implements ControlInterface
{
    private $size;
    
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
        $size = !empty($this->size) ? $this->size : '3';
        return "<h{$size} id=\"{$this->name}\" class=\"p-5 {$this->attributes}\">{$this->content}</h{$size}>";
    }

}
