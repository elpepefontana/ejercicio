<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;

class ImageMagnifier extends AbstractControl implements ControlInterface
{
    private $path;
    
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
    
    public function  render()
    {
        $path = strtolower($this->path);
        $content  = "<div class=\"imagemagnifier{$this->css_class}\" data-magnifier-mode=\"glass\" data-lens-type=\"circle\" data-lens-size=\"200\" {$this->attributes}>\n";
        $content .= "   <img class=\"h-100\" src=\"{$path}{$this->name}\">\n";
        $content .= "</div>\n";
        return $content;
    }
    
}
