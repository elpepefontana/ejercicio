<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface;

class Decimal extends AbstractInput implements InputInterface
{
    public function __construct(string $formName, string $formFather, array $typeData, array $data)
    {
        parent::__construct($formName, $formFather, $typeData, $data);
    }
    
    public function render(): string
    {
        $textName       = !empty($this->name) ? "id=\"{$this->name}\" name=\"{$this->name}\"" : '';
        $textAttributes = !empty($this->attributes) ?  $this->defaultTextAttributes() . $this->attributes : $this->defaultTextAttributes();
        return "<input type=\"text\" {$textName} class=\"{$this->css_class}\" value=\"{$this->value}\" {$textAttributes}>\n";
    }
    
    private function defaultTextAttributes()
    {
        return "data-role=\"input\" ";
    }

    public function data(): array
    {
        $out = $this->getData();
        
        $out['kind'] = "input";
        $out['type'] = "text";
        $out['data-role'] = "input";
        $out['data-prepend'] = $this->label;
        
        $this->validationText .= ' Solo números decimales.';
        $out['validation_text'] = $this->validationText;
        
        $typeValidate = " float";
        if(!empty($this->validate)) {$out['data-validate'] = $this->validate . $typeValidate;}
        if(!empty($this->default)) {$out['data-default-value'] = $this->default;}
        if(!empty($this->name) && !empty($this->value)) {$out['value'] = $this->value;}
        return $out;
    }

}