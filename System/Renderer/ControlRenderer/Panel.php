<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface as ControlInterface;

class Image extends AbstractControl implements ControlInterface
{
    public function __construct(array $panelData)
    {
        $this->setData($panelData);
    }
    
    private function setData($panelData)
    {
        foreach ($panelData as $key => $data) {
            if (!property_exists(__CLASS__, $key)) {
                continue;
            }
            $this->$key = $data;
        }
    }
    
    public function render(): string
    {
        $name = $this->name !== '' ? "id=\"{$this->name}\" name=\"{$this->name}\"" : '';
        return "<div {$name} data-role=\"panel\" {$this->attributes} {$this->css_class} >\n{$this->content}</div>\n";
    }

}
