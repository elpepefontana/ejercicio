<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface;

class Hidden extends AbstractInput implements InputInterface
{
    public function __construct(string $formName, string $formFather, array $typeData, array $data)
    {
        parent::__construct($formName, $formFather, $typeData, $data);
    }
    
    public function render(): string
    {
        $textName = !empty($this->id) ? "id=\"{$this->id}\" name=\"{$this->name}\"" : '';
        return "<input type=\"hidden\" {$textName} value=\"{$this->value}\">\n";
    }

    public function data(): array
    {
        $out = $this->getData();
        
        $out['kind'] = "input";
        $out['type'] = "hidden";
        
        if (empty($this->value) && empty($this->default)) {
            return $out;
        }
        
        if (!empty($this->default)) {
            $finalValue = $this->default;
        }
        
        if (!empty($this->value)) {
            $finalValue = $this->value;
        }
        
        if(!empty($this->name) && !empty($finalValue)) {$out['value'] = $finalValue;}
        
        return $out;
    }

}
