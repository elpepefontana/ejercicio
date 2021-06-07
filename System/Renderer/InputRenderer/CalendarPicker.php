<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface;

class CalendarPicker extends AbstractInput implements InputInterface
{
    public function __construct(string $formName, string $formFather, array $typeData, array $data)
    {
        parent::__construct($formName, $formFather, $typeData, $data);
    }
    
    public function render(): string
    {
        return "<input data-role=\"calendarpicker\" id=\"{$this->id}\" name=\"{$this->name}\" class=\"$this->css_class\" {$this->attributes}>\n";
    }

    public function data(): array
    {
        $out = $this->getData();
        
        $out['kind'] = "input";
        $out['data-role'] = "calendarpicker";
        $out['data-prepend'] = $this->label;
        $out['data-locale'] = 'es-AR';
        $this->validationText .= ' Completar con datos y formato de fecha correctos.';
        $out['validation_text'] = $this->validationText;
        $out['data-locale'] ="es-MX";
        $out['data-value-format'] = "%d/%m/%y";
        
        if(!empty($this->default)) { $out['data-default-value'] = $this->default; }
        
        $typeValidate = "date ";
        if(!empty($this->validate)) {$out['data-validate'] = $this->validate . $typeValidate;}
        
        if(!empty($this->name) && !empty($this->value)) {$out['value'] = $this->value;}
        return $out;
    }

}