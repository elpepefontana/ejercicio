<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;
use System\Core\Controls as Controls;

class Menu extends AbstractControl implements ContentInterface
{
    private $kind;
    private $json;
    private $home = false;
    private $exit = false;
    private $shadow = false;
    
    private $menu;
    
    public function __construct($typeData, $data)
    {
        parent::__construct($typeData, $data);
        $this->setData();
        parent::addData('kind', 'h-menu');
        $this->menu = new \System\Core\Menu(new System\Core\Session());
    }
    
    public function setData($objectData)
    {
        foreach ($objectData as $key => $value) {
            if (!property_exists(__CLASS__, $key)) {
                continue;
            }
            $this->$key = $value;
        }
        $this->json = $this->menu->genMenu($this->name);
    }
    
    public function render()
    {
        $menu = json_decode($this->json, false);
        if (!is_object($menu)) { 
            return ''; 
        }
        $menuKind = !empty($this->kind) ? $this->kind : 'h-menu';
        $shadow   = $this->shadow ? 'drop-shadow' : '';
        $content  = "<ul class=\"{$menuKind} $shadow bg-{$menu->back_color} fg-{$menu->font_color} py-2 px-2\" {$this->attributes} >\n";
        $content .= $this->home === true ? "   <li><a href=\"" . HOME . "/AdminHome\"><span class=\"icon mif-home fg-{$menu->font_color}\" ></span></a></li>\n" : '';
        if (!is_array($menu->groups) || count($menu->groups) === 0 ) {
            $content .= $this->exit ? "   <li class=\"place-right\"><a href=\"" . HOME . "/Home/logOut\" class=\"app-bar-item place-right\"><span class=\"icon mif-exit fg-{$menu->font_color}\"></span></a></li>" : '';
            $content .= "</ul>";
            return $content;
        }
        $groups = $menu->groups;
        foreach ($groups as $group) {
            $group->back_color = $menu->back_color; 
            $group->font_color = $menu->font_color;
            if (!is_array($group->items) || count((array)$group->items) == 0) {
                $content .= "    <li><a href=\"" . HOME . "/{$group->link}\"><span class=\"icon mif-{$group->icon} fg-{$group->font_color} pl-4 pr-4\" ></span>{$group->title}</a></li>\n";
                continue;
            }
            $content .= "<li>";
            $content .= "    <a href=\"#\" class=\"dropdown-toggle\">{$group->title}</a>\n";
            $content .= "    <ul class=\"d-menu bg-{$group->back_color} fg-{$group->font_color}\" data-role=\"dropdown\">\n";
            foreach ($group->items as $item) {
                $item->back_color = $menu->back_color; 
                $item->font_color = $menu->font_color;
                $content .= "               <li><a href=\"" . HOME . "/{$item->link}\"><span class=\"icon mif-{$item->icon} fg-{$item->font_color}\"></span>&nbsp;&nbsp;{$item->title}</a></li>\n";; 
            }
            $content .= "    </ul>\n";
            $content .= "</li>\n";
        }
        $content .= $this->exit ? "   <li class=\"place-right\"><a href=\"" . HOME . "/Home/logOut\" class=\"app-bar-item place-right\"><span class=\"icon mif-exit fg-{$menu->font_color}\"></span></a></li>" : '';
        $content .= "</ul>";
        return $content;
    }

}
