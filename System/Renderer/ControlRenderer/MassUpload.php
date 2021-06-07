<?php

namespace System\Renderer\ControlRenderer;

use System\Core\Controls as Controls;
use System\Renderer\ControlRenderer\ControlInterface as ControlInterface;

class MassUpload extends AbstractContent implements ControlInterface
{
    private $title;
    
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
        $select     = Controls::drawArrayToSelect("slt_{$config[0]['name']}", '', $data, '', true, '', 'data-prepend="Seleccione"');
        $selectTitleDiv  = Controls::drawDiv("div_{$config[0]['name']}_title", 'd-inline mx-3 mb-3', "{$params['titulo']}: ", 'style="width: 20%;display:inline-block;"');
        $selectDiv  = Controls::drawDiv("div_{$config[0]['name']}", 'mx-3 mb-3', $selectTitleDiv . $select, '');
        $massiveUpl = Controls::drawFileDrop("mass_{$config[0]['name']}", $config[0]['css_class'], $config[0]['attributes']);
        $button     = Controls::drawSubmitButton("sbt_{$config[0]['name']}", "button success m-3", 'Cargar', 'data-button-title="Seleccione"');
        $buttonDiv  = Controls::drawDiv("div_{$config[0]['name']}", 'd-flex flex-justify-center', $button, '');
        $form       = Controls::drawForm("frm_{$config[0]['name']}", 'POST', '', $selectDiv . $massiveUpl . $buttonDiv , 'enctype="multipart/form-data"');
        $content   .= Controls::drawModal("{$config[0]['name']}Modal", 'Subida masiva', 'bg-darkBlue fg-white', $form, '', '', '500');
        return $content;
    }

}
