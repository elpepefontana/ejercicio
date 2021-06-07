<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface;

class LookUpVar extends AbstractInput implements InputInterface
{
    public function __construct(string $formName, string $formFather, array $typeData, array $data)
    {
        parent::__construct($formName, $formFather, $typeData, $data);
    }
    
    public function render(): string
    {
        return "";
    }

    public function data(): array
    {
        $out = $this->getData();
        
        $out['kind'] = "input";
        $out['type'] = "text";
        $out['data-role'] = "select";
        $out['data-prepend'] = $this->label;
        
        $typeValidate = "";
        if (!empty($this->validate)) {$out['data-validate'] = $this->validate . $typeValidate;}
        if (!empty($this->default)) {$out['data-default-value'] = $this->default;}
        if (!empty($this->name) && !empty($this->value)) {$out['value'] = $this->value;}
        return $out;
    }

}
