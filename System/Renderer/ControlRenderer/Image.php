<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface as ControlInterface;

class Image extends AbstractControl implements ControlInterface
{
    private $path;
    private $width = '';
    private $height = '';
    private $overlay = '';
    private $overlayColor = '';
    
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
        $imagePath = strtolower($imagePath);
        $width  = !empty($this->width) ? "width: $this->width;" : '';
        $height = !empty($this->height) ? "height: $this->height;" : '';
        $style  = !empty($width) && !empty($height) ? "style=\"{$width}{$height}\"" : '';
        $content  = "<div id=\"img_{$this->name}\" class=\"img-container {$this->css_class}\" {$this->attributes}>\n";
        $content .= "   <img src=\"{$imagePath}{$this->name}\" data-src=\"{$this->path}{$this->name}\" {$style}>\n";
        $content .= !empty($this->overlay) ? $this->drawImageOverLay($this->overlay) : '';
        $content .= "</div>\n";
        return $content;
    }
    
    private function drawImageOverLay()
    {
        $content  = '<div class="image-overlay op-' . $this->overlayColor . '">';
        $content .= $this->overlay; 
        $content .= '</div>';
        return $content;
    }

}
