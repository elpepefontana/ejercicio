<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;

class Text extends AbstractControl implements ControlInterface
{
    private $title;
    
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
        $content = !empty($this->title) ? "<h3>{$this->title}<h3>" : "";
        $content .= "<p>{$this->content}</p>";
        return $content;
    }

}
