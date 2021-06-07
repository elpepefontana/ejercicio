<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface;

class TextArea extends AbstractInput implements InputInterface
{
    public function __construct(string $formName, string $formFather, array $typeData, array $data)
    {
        parent::__construct($formName, $formFather, $typeData, $data);
    }
    
    public function render(): string
    {
        $areaName = !empty($this->name) ? "id=\"{$this->id}\" name=\"{$this->name}\"" : '';
        $areaAttributes = !empty($this->attributes) ? " data-role=\"textarea\" " . $this->attributes : "data-role=\"textarea\"";
        return "<textarea {$this->css_class} {$areaName} {$areaAttributes}>{$this->value}</textarea>\n";
    }

    public function data(): array
    {
        $out = $this->getData();
        
        $out['kind'] = "textarea";
        $out['data-role'] = "textarea";
        $out['data-prepend'] = $this->label;
        
        $this->validationText .= 'Texto máximo ' . $this->size . '.';
        $out['validation_text'] = $this->validationText;
        
        $typeValidate = "";
        if (!empty($this->validate)) {$out['data-validate'] = $this->validate . $typeValidate;}
        if (!empty($this->default)) {$out['data-default-value'] = $this->default;}
        if (!empty($this->name) && !empty($this->value)) {$out['value'] = $this->value;}
        return $out;
    }

}
