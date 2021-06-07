<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface;

class Email extends AbstractInput implements InputInterface
{
    public function __construct(string $name, string $father, array $typeData, array $data)
    {
        parent::__construct($name, $father, $typeData, $data);
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
        
        $this->validationText .= ' Ingrese un email válido.';
        $out['validation_text'] = $this->validationText;
        
        $typeValidate = " email";
        if(!empty($this->validate)) {$out['data-validate'] = $this->validate . $typeValidate;}
        if(!empty($this->default)) {$out['data-default-value'] = $this->default;}
        if(!empty($this->name) && !empty($this->value)) {$out['value'] = $this->value;}
        return $out;
    }

}
