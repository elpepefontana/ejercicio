<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;
use System\Core\Controls as Controls;

class Image extends AbstractControl implements ControlInterface
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
    
    public function render(): string
    {
        $carouselClass = !empty($this->css_class) ? "class=\"{$this->css_class}\"" : '';
        return "<div data-role=\"carousel\" id=\"carousel_{$this->name}\" "
                . "{$carouselClass} data-cls-controls=\"fg-black\" "
                . "data-control-next=\"<span class='mif-chevron-right'></span>\" "
                . "data-control-prev=\"<span class='mif-chevron-left'></span>\" "
                . "{$this->attributes}>"
                . "{$this->content}"
            . "</div>\n";
    }
    
    public static function genCarouselContent(array $aImages, bool $link): string
    {
        $out = '';
        $it = 0;
        foreach ($aImages as $img) {
            $cols   = $img['frame'] === 'landscape' ? 10 : 5;
            $image  = Controls::drawImage($img['photo'], GALLERY_PATH, '', '');
            $holder = $link ? Controls::drawLink($img['photo'],"", '', $image, '') : $image;
            $cell = Controls::drawCell('', '', $holder, '', $cols);
            $row  = Controls::drawRow('', 'd-flex flex-justify-center', $cell, '');
            $out .= "<div class=\"slide\">{$row}</div>\n";
            $it++;
        }
        return $out;    
    }

}
