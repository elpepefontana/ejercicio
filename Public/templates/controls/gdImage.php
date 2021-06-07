<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;

class GDImage extends AbstractControl implements ControlInterface
{
    private $path;
    private $caption;
    private $captionAlign;
    private $imagerType = '';
    
    private $image;
    private $gdImage;
    private $fileManager;

    public function __construct($typeData, $data)
    {
        parent::__construct($typeData, $data);
        $this->setData();
        $this->image = new System\CodeGen\ControlRenderer\Image($this->typeData);
        $this->fileManager = new \System\Core\FileManager();
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
    
    public function render()
    {
        $content  = "<div id=\"img_{$imageName}\" class=\"img-container {$imageClass}\">\n";
        $content .= "   <img src='data:image/jpeg;base64," . $this->image ."'>\n";
        $content .= "</div>/n";
        return $content;
    }

}
