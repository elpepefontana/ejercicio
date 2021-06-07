<?php

namespace System\Core;

use System\Renderer\InputRenderer\InputRenderer as InputRenderer; 

class FormFactory
{
    use \System\Traits\UtilitiesTrait;
    
    private $db;
    private $qb;
    
    private $column;
    
    private $session;
    private $actionType;
    private $formName;
    private $objectName;
    private $formMethod;
    private $formAction;
    private $formThreshold;
    private $formFather;
    private $parentId;
    private $data;
    
    private $factory;
    
    public function __construct(
            string $actionType,
            string $formName, 
            $session, 
            string $formMethod = 'POST', 
            string $formAction, 
            int $formThreshold = 10, 
            string $formFather = '',
            $parentId,
            array $data
    ) {
        $this->session = $session;
        
        $this->factory = new \System\Factories\Factory();
        
        $modelData = $this->setModelData('column', $this->session, 'codegen');
        $this->column = $this->factory->create($modelData);
        
        $this->setActionType($actionType);
        $this->setFormName($formName);
        $this->setFormMethod($formMethod);
        $this->setFormAction($formAction);
        $this->setFormThreshold($formThreshold);
        $this->setFormFather($formFather);
        $this->setParentId($parentId);
        $this->setData($data);
    }
    
    private function setModelData($object, $session, $subType)
    {
        $data = new \stdClass();
        $data->factoryType = 'model';
        $data->object = $object;
        $data->action = '';
        $data->session = $session;
        $data->subType = $subType;
        return $data;
    }
    
    public function setActionType($actionType)
    {
        $this->actionType = $actionType;
    }
    
    public function setFormName($formName)
    {
        $this->formName = $formName;
        $this->objectName = $formName;
    }
    
    public function setObjectName(array $objectName)
    {
        $a = 0;
        $out = '';
        foreach($objectName as $obj) {
            if ($a > 0) {
                $out .= empty($out) ? $obj : "_" . $obj;
            }
            $a++;
        }
        $this->objectName = $out;
    }
    
    public function setFormMethod($formMethod)
    {
        $this->formMethod = $formMethod;
    }
    
    public function setFormAction($formAction)
    {
        $this->formAction = $formAction;
    }
    
    public function setFormThreshold($formThreshold)
    {
        $this->formThreshold = $formThreshold - 1;
    }
    
    public function setData($value) 
    {
        if (is_string($value)) {
            $value = json_decode($value, true);
        }
        $this->data = $value;
    } 
    
    public function setFormFather($formFather)
    {
        $this->formFather = $formFather;
    }
    
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }
    
    //GETTERS
    public function getActionType()
    {
        return $this->actionType;
    }
    
    public function getFormName()
    {
        return $this->formName;
    }
    
    public function getObjectName()
    {
        return $this->objectName;
    }
    
    public function getFormMethod()
    {
        return $this->formMethod;
    }
    
    public function getFormAction()
    {
        return $this->formAction;
    }
    
    public function getFormThreshold()
    {
        return $this->formThreshold;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getFormFather()
    {
        return $this->formFather;
    }
    
    public function getParentId()
    {
        return $this->parentId;
    }
    
    public function drawForm()
    {
        $it    = 0;
        $total = 0;
        $fields = $this->column->searchFieldsByTableNameWithNoChild($this->objectName);
        $fieldCount        = count($fields) - 1 ;
        $completeIteration = ceil($fieldCount / $this->getFormThreshold());
        $width          = 12 / $completeIteration;
        $formContent    = '';
        $controlContent = '';
        foreach ($fields as $field) {
            $fieldControl =  $this->actualRendererCall($field, 'render');
            $controlContent .= Controls::drawDiv('', 'p-2', $fieldControl , "");
            if ($this->getFormThreshold() != $it && $fieldCount != $total) {
                $it += 1;
                continue;
            }
            $formContent   .= Controls::drawDiv('', "cell-{$width}", $controlContent, "");       
            $fieldControl   = '';
            $controlContent = '';
            $it = 0;
            $total += 1;    
        }
        $formContent = Controls::drawDiv('', 'row', $formContent, '');
        $action      = $this->getActionType() === 'create' ? "createObjectData('<?=HOME;?>', '{$this->getFormName()}', '');" : "changeObjectData('<?=HOME;?>', '{$this->getFormName()}', '', '');";
        $submit      = Controls::drawButton('', 'success', 'Enviar', "onclick=\"{$action}\" ");
        $buttonDiv   = Controls::drawDiv('', 'd-flex flex-justify-center', $submit, '');
        $buttonCell  = Controls::drawCell('', '', $buttonDiv, '', 12, '');
        $buttonRow   = Controls::drawDiv('', 'row', $buttonCell , '');
        $form        = Controls::drawForm("frm_{$this->getActionType()}_{$this->getObjectName()}", $this->getFormMethod(), $this->getFormAction(), $formContent . $buttonRow, '');
        $content     = Controls::drawDiv("div_{$this->getFormName()}",'grid', $form, '');
        return $content;
    }
    
    public function drawJsonForm()
    {
        $out['id']         = $this->getFormName();
        $out['name']       = $this->getFormName();
        $out['method']     = $this->getFormMethod();
        $out['action']     = $this->getFormAction();
        $out['threshold']  = $this->getFormThreshold();
        $out['objectname'] = $this->getObjectName();
        $out['fathername'] = $this->getFormFather();
        $out['data-role']  = "validator";
        $out['data-interactive-check'] = true;
        
        $fields = $this->column->searchFieldsByTableNameWithNoChild($this->objectName);
        
        foreach ($fields as $field) {
            $out['fields'][] = $this->actualRendererCall($field, 'data');
        }
        return $out;
    }
    
    private function actualRendererCall($field, $method)
    {
        if (!method_exists('\\System\\Renderer\\InputRenderer\\InputRenderer', $method)) {
            return;
        }
        
        $renderer = new InputRenderer(
            $this->session,
            $this->getFormName(),
            $this->getFormFather(),
            $field,   
            $this->getData()
        );

        $renderer->create();
        return $renderer->$method();
    }
}