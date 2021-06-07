<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface;

class Text extends AbstractInput implements InputInterface
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
        
        $this->validationText .= 'Texto mínimo 3, máximo ' . $this->size . '.';
        $out['validation_text'] = $this->validationText;
        if(!empty($this->default)) {$out['data-default-value'] = $this->default;}
        
        $typeValidate = strpos($this->validate, 'required') !== false ? ' minlength=3' : '';
             
        if(!empty($this->validate)) {$out['data-validate'] = $this->validate . $typeValidate;}
        return $out;
    }

}
