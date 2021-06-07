<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;

class Video extends AbstractControl implements ContentInterface
{
    private $source;
    private $logoSrc = '';
    private $logoHeight = '';
    private $link = '';
    private $hideControls = '3000';
    
    public function __construct($typeData, $data)
    {
        parent::__construct($typeData, $data);
        $this->setData();
        parent::addData('source', $data['value']);
        parent::addData('logoSrc', $data['text']);
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
        $name     = !empty($this->name) ? "id=\"{$this->name}\"" : "";
        $content  = "<video {$name} data-role=\"video\"";
        $content .= !empty($this->source) ? "data-src=\"{$this->source}\"" : "";
        $content .= !empty($this->logoSrc) ? "data-logo=\"{$this->logoSrc}\"" : "";
        $content .= !empty($this->logoHeight) ? "data-logo-height=\"{$this->logoHeight}\"" : "";
        $content .= !empty($this->link) ? "data-logo-target=\"{$this->link}\"" : "";
        $content .= $this->hideControls ? "data-controls-hide=\"{$this->hideControls}\"" : "";
        $content .= "></video>";
        return $content;
    }

}
