<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;

class Modal extends AbstractControl implements ControlInterface
{
    private $title;
    private $width = '90';
    private $closeObject = '';
    private $contentAttributes = '';
    private $contentClass = 'p-5';
    private $zIndex = 500;
    
    public function __construct($typeData, $data)
    {
        parent::__construct($typeData, $data);
        $this->setData();
        parent::addData('title', $data['text']);
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
    
    public function render(): string
    {
        $contentAttributes = empty($this->contentAttributes) ? "style=\"clear: both;overflow-x: auto;white-space: nowrap;\"" : $this->contentAttributes;
        $width    = !empty($this->width) ? "width: {$this->width}%;" : '';
        $content  = "<div id=\"{$this->name}Modal\" class=\"Modal\" style=\"z-index: {$this->zIndex};\">\n";
        $content .= "    <div id=\"{$this->name}Content\" class=\"Modal-content\" style=\"{$width}\">\n";
        $content .= "        <div class=\"{$this->css_class}\" style=\"height: 45px;font-size: 20px; font-weight: bold\">\n";
        $content .= "            <div id=\"{$this->name}ModalTitle\" class=\"px-3 py-2 mb-3\" style=\"float: left;width: 90%\">\n";
        $content .= utf8_decode($this->title) . "\n";
        $content .= "            </div>\n";
        $content .= "            <div class=\"px-3\" style=\"float: left;width: 10%;\">\n";
        $content .= "                <span id=\"{$this->name}Close\" class=\"ModalClose\" onclick=\"closeModal('{$this->name}', {$this->closeObject})\">&times;</span><p>&nbsp;</p>\n";
        $content .= "            </div>\n";
        $content .= "        </div>\n";
        $content .= "       <div id=\"{$this->name}ModalContent\" class=\"{$this->contentClass}\" {$contentAttributes}>\n";
        $content .= $this->content;
        $content .= "       </div>\n";
        $content .= "    </div>\n";
        $content .= "</div>\n";
        return $content;
    }
    
}
