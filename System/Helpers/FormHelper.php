<?php

namespace System\Helpers;

class FormHelper 
{
    
    private $formName;
    private $formFather;
    
    // VALIDATION FUNCTIONS - FUNCIONES DE VALIDACION 
    public function __construct($formName, $formFather) 
    {
        $this->setFormName($formName);
        $this->setFormFather($formFather);
        createForm();
    }
    
    public function setFormName($value)
    {
        $this->formName = $value;
    }
    
    public function setFormFather($value)
    {
        $this->formFather = $value;
    }
    
    public function getFormName()
    {
        return $this->formName;
    }
    
    public function getFormFather()
    {
        return $this->formFather;
    }
    
    public function createForm()
    {
        $realForm = new \System\Core\FormFactory($this->getFormName, $this->session, 'POST', '', 10, $this->getFormFather(), []);
        echo $realForm->drawForm();
    }
    
    
}
