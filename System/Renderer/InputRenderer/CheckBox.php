<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface;

class CheckBox extends AbstractInput implements InputInterface
{
    public function __construct(string $formName, string $formFather, array $typeData, array $data)
    {
        parent::__construct($formName, $formFather, $typeData, $data);
    }
    
    public function render(): string
    {
        $checkName = !empty($checkName) ? "id=\"{$this->id}\" name=\"{$this->name}\"" : '';
        $checkAttributes = $this->attributes !== '' ? "data-role=\"checkbox\" " . $this->attributes : "data-role=\"checkbox\""; // data-role=\"switch\"
        return "<input type=\"checkbox\" {$checkName} {$checkAttributes}>";
    }

    public function data(): array
    {
        $out = $this->getData();
        
        $out['kind'] = "input";
        $out['type'] = "checkbox";
        $out['data-role'] = "checkbox";
        $out['data-caption'] = $this->label;
        $out['data-caption-position'] = "left";
        $this->validationText .=  ' ';
        $out['validation_text'] = $this->validationText;
        if (!empty($this->validate)) { $out['data-validate'] = $this->validate; }
        if (!empty($this->name) && $this->value == 1) { $out['checked'] = 'checked'; }
        return $out;
    }

}