<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface;

class ImageDrop extends AbstractInput implements InputInterface
{
    public function __construct(string $formName, string $formFather, array $typeData, array $data)
    {
        parent::__construct($formName, $formFather, $typeData, $data);
    }
    
    public function render(): string
    {
        $imagePath = strtolower($imagePath);
        $width  = !empty($imageWidth) ? "width: $imageWidth;" : '';
        $height = !empty($imageHeight) ? "height: $imageHeight;" : '';
        $style  = !empty($width) && !empty($height) ? "style=\"{$width}{$height}\"" : '';
        $content  = "<div data-mode=\"drop\" id=\"img_{$this->id}\" class=\"img-container {$this->css_class}\" {$this->$attributes}>\n";
        $content .= "   <img src=\"" . GALLERY_PATH . "{$this->name}\" data-src=\"" . GALLERY_PATH . "{$this->name}\" {$style}>\n";
        $content .= !empty($overlay) ? $this->drawImageOverLay($overlay) : '';
        $content .= "</div>\n";
        return $content;
    }
    
    private function drawImageOverLay($ImageOverlayContent)
    {
        $content  = '<div class="image-overlay op-white">';
        $content .= $ImageOverlayContent; 
        $content .= '</div>';
        return $content;
    }

    public function data(): array
    {
        $out = $this->getData();
        
        $out['kind'] = "input";
        $out['type'] = "file";
        $out['data-role'] = "file";
        $out['data-prepend'] = $this->label;
        $out['data-mode'] = "drop";
        
        $this->validationText .= '';
        $out['validation_text'] = $this->validationText;
        
        $typeValidate = "";
        if(!empty($this->validate)) {$out['data-validate'] = $this->validate . $typeValidate;}
        if(!empty($this->default)) {$out['data-default-value'] = $this->default;}
        if(!empty($this->name) && !empty($this->value)) {$out['value'] = $this->value;}
        return $out;
    }

}
