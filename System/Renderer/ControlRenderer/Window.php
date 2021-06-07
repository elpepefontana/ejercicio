<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface as ControlInterface;

class Window extends AbstractContent implements ControlInterface
{
    private $icon;
    private $title = '';
    private $minimize = true;
    private $maximize = true;
    private $close = true;
    
    public function __construct($typeData, $data)
    {
        parent::__construct($typeData, $data);
        $this->setData();
    }
    
    public function setData()
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
        $content  = "<div id=\"win_{$this->name}\" class=\"window\" data-role=\"window\">";
        $content .= "   <div class=\"window-caption\">";
        $content .= !empty($this->icon) ? "       <span class=\"icon {$this->icon}\"></span>" : "";
        $content .= "       <span id =\"win_{$this->name}Title\" class=\"title\">{$this->title}</span>";
        $content .= "       <div class=\"buttons\">";
        $content .= $this->minimize ? "           <span class=\"btn-min\"></span>" : "";
        $content .= $this->maximize ? "           <span class=\"btn-max\"></span>" : "";
        $content .= $this->close ? "           <span class=\"btn-close\"></span>" : "";
        $content .= "       </div>";
        $content .= "   </div>";
        $content .= "   <div id=\"win_{$this->name}Content\" class=\"window-content p-2\">";
        $content .= $this->content;
        $content .= "   </div>";
        $content .= "</div>";
        return $content;
    }

}
