<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface;

class EnumInt extends AbstractInput implements InputInterface
{
    use \System\Traits\UtilitiesTrait;
    
    public function __construct(string $formName, string $formFather, array $typeData, array $data)
    {
        parent::__construct($formName, $formFather, $typeData, $data);
    }
    
    public function render(): string
    {
        if (!is_array($this->data) || count($this->data) == 0) {
            return '';
        }
        $selectAttributes = $this->attributes !== '' ? 'data-role="select" ' . $this->attributes : 'data-role="select"';
        $content  = "<select id=\"{$this->name}\" name=\"{$this->name}\" class=\"{$this->css_class}\" {$selectAttributes}>"; 
        $content .= "<option value=\"NULL\">(ninguno)</option>";
        foreach ($sthis->data as $opt) {
            switch (gettype($opt)) {
                case 'string':
                    $sel  = $selected === $opt ? "selected=\"selected\"" : "";
                    $text = $firstLetter === true ? ucfirst(utf8_decode($opt)) : utf8_decode($opt);
                    $content .= "    <option value=\"" . utf8_decode($opt) . "\" {$sel} >" .$text  . "</option>";
                    break;
                case 'array':
                    if (isset($opt) && (!empty($opt['DisplayText']) || !empty($opt['Value']))) {
                        $sel  = $selected === $opt['Value'] || $selected === $opt['DisplayText'] ? "selected=\"selected\"" : "";
                        $attr = isset($opt['attr']) ? 'attr="' . $opt['attr'] . '"' : '';
                        if (empty($opt['DisplayText']) && !empty($opt['Value'])) {
                            $text = $firstLetter === true ? ucfirst(utf8_decode($opt['Value'])) : utf8_decode($opt['Value']);
                            $content .= "    <option value=\"" . utf8_decode($opt['Value']) . "\" {$sel} {$attr}>" . $text . "</option>";
                        } elseif(!empty($opt['DisplayText']) && empty($opt['Value'])) {
                            $text = $firstLetter === true ? ucfirst(utf8_decode($opt['DisplayText'])) : utf8_decode($opt['DisplayText']);
                            $content .= "    <option value=\"" . utf8_decode($opt['DisplayText']) . "\" {$sel} {$attr}>" . $text . "</option>";
                        } else {
                            $text = $firstLetter === true ? ucfirst(utf8_decode($opt['DisplayText'])) : utf8_decode($opt['DisplayText']);
                            $content .= "    <option value=\"" . utf8_decode($opt['Value']) . "\" {$sel} {$attr}>" . $text . "</option>";
                        }  
                    } 
                    break;
            }
        }
        $content .= "</select>";
        return $content;
    }

    public function data(): array
    {
        $out = $this->getData();
        
        $targetData = $this->setTargetedFields();
        
        $out['kind'] = "select";
        $out['data-role'] = "select";
        $out['data-prepend'] = $this->label;
        
        $this->validationText .= 'Por favor seleccione opción.';
        $out['validation_text'] = $this->validationText;
        
        $out['selectType'] = 'enumint';
        $out['selectObjectTarget'] = $this->toCamelCase($targetData->name, true);
        $out['selectKeyField'] = $targetData->field;
        $out['data-on-change'] = "iterateTroughSelectsAndExecuteFilters('{$this->formName}')"; 
        
        if (!empty($this->default)) { $out['data-default-value'] = $this->default; }
        
        $typeValidate = "";
        if (!empty($this->validate)) {$out['data-validate'] = $this->validate . $typeValidate;}
        
        if (!empty($this->name) && !empty($this->value)) {$out['selectValue'] = $this->value;}
        return $out;
    }
    
    private function setTargetedFields($separator = "' '")
    {
        $targetData = explode('|', $this->typeData['externo']);
        $i = 0;
        $obj = new \stdClass();
        foreach ($targetData as $target) {
            if ($i == 0) {
                $obj->name = $target;
                $i += 1;
                continue;
            }
            $obj->field = $target;
            if ($i >= 2) {
                $obj->field = " CONCAT(`{$obj->field}` , '{$separator}' , `{$target}`) {$this->name}";
            }
            $i += 1; 
        }
        return $obj;
    }

}