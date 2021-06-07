<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;
use System\Core\Controls as Controls;

class Figure extends AbstractControl implements ControlInterface
{
    private $path;
    private $caption;
    private $captionAlign;
    
    private $image;
    
    public function __construct($typeData, $data)
    {
        parent::__construct($typeData, $data);
        $this->setData();
        parent::addData('path', $data['value']);
        parent::addData('caption', $data['title']);
        parent::addData('captionAlign', $data['text']);
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
        $content  = "<figure>/n";
        $content .= $this->image->render();
        if (!empty($this->caption) && !is_array($this->caption)) { 
            $content .= "<figcaption>{$this->caption}</figcaption>/n"; 
        } elseif (!empty($this->caption) && is_array($this->caption)) {
            foreach ($this->caption as $caption) {    
                $content .= !empty($this->captionAlign) ? "    <figcaption class=\"{$this->captionAlign}\">/n" : "<figcaption>/n";
                $content .= "        {$caption}/n";
                $content .= "    </figcaption>/n";
            }
        }
        $content .= "</figure>/n";
        return $content;
    }

}
