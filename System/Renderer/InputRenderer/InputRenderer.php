<?php

namespace System\Renderer\InputRenderer;

use System\Core\FileManager as FileManager;
use System\Core\Session as Session;
use System\Renderer\RendererInterface as RendererInterface;

class InputRenderer implements RendererInterface
{
    use \System\Traits\UtilitiesTrait;
    
    private $session;
    private $formName;
    private $formFather;
    private $data;
    private $params;
    
    private $subTypeData;
    
    private $context;
    
    public function __construct(
        Session $session,
        string $formName,
        string $formFather,
        array $data,
        array $params
    ) {
        $this->session = $session;
        $this->formName = $formName;
        $this->formFather = $formFather;
        $this->data = $data;
        $this->params = $params;
        
        $this->context  = new InputContext();
        
        $this->setSubTypeData();
    }
    
    public function setSubTypeData()
    {
        if (!is_file(INPUT_RENDERER_PATH . 'inputsubtypelist.json')) {
            $this->subTypeData = null;
            return;
        }
        $this->subTypeData = FileManager::readJsonFile(
            INPUT_RENDERER_PATH, 
            'inputsubtypelist.json', 
            true
        );
    }
    
    public function create()
    {
        $className = $this->setClassName($this->data['tipo']);
        
        if (empty($className)) {
            return '';
        }
        
        $this->context->set(
            new $className(
                $this->formName, 
                $this->formFather, 
                $this->data, 
                $this->params
            )
        );
    }
    
    public function setClassName($classSubtype): string
    {
        return '\\System\\Renderer\\InputRenderer\\' . $this->getSubtype($classSubtype);
    }
    
    public function getSubtype($classSubtype): ?string
    {
        if (!array_key_exists($classSubtype, $this->subTypeData)) {
            return null;
        }
        
        return $this->subTypeData[$classSubtype];
    }
    
    public function data()
    {
        return $this->context->data();
    }
    
    public function render()
    {
        return $this->context->render();
    }
}
