<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;

class Lists extends AbstractControl implements ControlInterface
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
        $listName = !empty($this->name) ? "id=\"{$this->name}\" name=\"{$this->name}\"" : '';
        $content  = "<ul {$listName} 
                    data-role=\"list\" 
                    data-show-search=\"true\" 
                    data-cls-list=\"unstyled-list row flex-justify-center mt-4\"
                    data-cls-list-item=\"cell-sm-6 cell-md-4\" class=\"text-center\">\n";
        $content .= $this->content;
        $content .= "</ul>\n";
        return $content;
    }
    
}
