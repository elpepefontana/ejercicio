<?php

namespace System\Renderer\InputRenderer;

abstract class AbstractInput
{
    protected $id;
    protected $name;
    
    protected $formName;
    protected $formfather;
    
    protected $kind;
    protected $label;
    protected $value;
    protected $css_class;
    protected $attributes;
    protected $validate;
    protected $validationText;
    protected $default;
    protected $size;
    protected $type;
    protected $externalData;
    
    protected $typeData;
    protected $data;
    
    protected $template;
    
    public function __construct(string $formName, string $formFather, array $typeData, array $data)
    {
        $this->formName = $formName;
        $this->formFather = $formFather;
        $this->typeData = $typeData;
        $this->data = $data;
        
        $this->template = new \System\Core\Template(new \System\Core\Session(), INPUT_PATH);
        
        $this->setData();
    }
    
    public function addData(
        string $adicionalDataName, 
        string $adicionalDataValue): void
    {
        $this->typeData[$adicionalDataName] = $adicionalDataValue;
    }
    
    public function setData()
    {
        $this->id = $this->typeData['nombre'];
        
        $this->name  = $this->typeData['nombre'];
        
        $this->kind = 'input';
        
        $this->label = $this->typeData['etiqueta'];
        
        $this->value = isset($this->data[$this->name]) && !empty($this->data[$this->name]) ? $this->data[$this->name] : '';
        
        $this->createFieldValidation();
        
        $this->setDefaultValue();
        
        $this->size = $this->typeData['longitud'];
        
        $this->type = $this->setType();
        
        $this->attributes = !empty($this->typeData['attributes']) ? $this->typeData['attributes'] : '' ;
        
        $this->css_class = !empty($this->typeData['css_class']) ? $this->typeData['css_class'] : '';
        
        $this->externalData = !empty($this->typeData['clave']) ? $this->typeData['clave'] : '';
    }
    
    public function getData() 
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'kind' => $this->kind,
            'label' => $this->label,
            'value' => $this->value,
            'validate' => $this->validate,
            'validation_text' => $this->validationText,
            'default' => $this->default,
            'size' => $this->size,
            'type' => $this->type,
            'attributes' => $this->attributes,
            'css_class' => $this->css_class
        );
    }
    
    private function createFieldValidation()
    {
        if ($this->typeData['nulo'] == 0) {
            $this->validate .= ' required ';
            $this->validationText .= 'Requerido. ';
        }
        $this->validate .= !empty($this->typeData['longitud']) ? ' maxlength=' . $this->typeData['longitud'] . ' ' : '';
    }
    
    private function setDefaultValue()
    {
        if (empty($this->value)) {
            return '';
        }
        
        $this->default = $this->value;
        
        if (strpos($this->typeData['icono'], '[FATHER]') !== false) {
            $this->default = strtolower($this->formFather);
        } 
        
        if (strpos($this->typeData['icono'], 'data.record.id') !== false || strpos($this->value, '[FATHER.ID]') !== false) {
            $this->default = $this->getParentId();
        }
        
        if (strpos($this->typeData['icono'], 'SESSION') !== false) { // [SESSION.USER]
            $res = str_replace(array('[', ']'), '', $this->value);
            list($session, $key) = explode('.', $res);
            $this->default = $this->session->get(strtolower($key));
        }
    }
    
    private function setType()
    {
        if ($this->name === 'id') {
            return 'HIDDEN';
        }
        if ((int) $this->typeData['mostrar'] === 0) {
            return 'HIDDEN';
        }
        if ((int)$this->typeData['excluir'] === 1) {
            return 'HIDDEN';
        }
        return $this->typeData['tipo'];
    }
}
