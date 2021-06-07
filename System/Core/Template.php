<?php

namespace System\Core;

use System\Resource\AbstractResource;
use System\Resource\CssResource;
use System\Resource\JsResource;
use System\Formater\DataFormater;

class Template
{
    
    use \System\Traits\UtilitiesTrait;
    
    private $template = '';
    private $data;
    private $session;
    private $templaTePath;
    private $css = '';
    private $js = '';
    
    private $templates = [];
    
    public function __construct(Session $session, string $templatePath = null)
    {
        $this->session = $session;
        $this->templaTePath = !is_null($templatePath) ? $templatePath : TEMPLATE_PATH;
    }
    
    /* 
     * valid template and subtemplate string: 
     *          {main template}|{subtemplate: subfolder}_{subtemplate: name}->{subtemplate: placeholder name}
     *                                                  
     * cadena de template y subtemplate valida: 
     *          {plantilla principal}|{subplantilla: subcarpeta}_{subplantilla: nombre}->{subplantilla: nombre placeholder}
     */
    
    public function load(string $name, $data): string
    {
        $this->setTemplatesAndHolders($name);
        
        $this->data = $this->transformData('Obj', $data);
        
        $this->createTemplate();
        
        $this->replaceHolderWithContent('JS', '', $this->js);
        
        $this->replaceHolderWithContent('CSS', '', $this->css);
        
        return $this->template;
    }
    
    private function setTemplatesAndHolders(string $name): void
    {
        foreach ($this->setTemplatesData($name) as $item) {
            if (strpos($item, '->') === false) {
                $out[] = array('template' => $item, 'desteny' => '');
                continue;
            }
            
            list($template, $desteny) = explode('->', $item);
            
            $out[] = array('template' => $template, 'desteny' => $desteny);
        }
        
        $this->templates = !empty($out) ? $out : [];
    }
    
    public function getTemplate(string $name): string
    {
        $view = $this->searchSubTemplateData($name);
        
        $session = $this->session;
        
        ob_start();
        
        require $this->genFilePath($name);
        
        $template = ob_get_contents();
        
        ob_end_clean();
        
        return $template;
    }
    
    private function setTemplatesData($name): array
    {
        if (substr_count($name , '|') === 0) {
            return array($name);
        }
        
        return explode('|', $name);
    }
    
    private function createTemplate(): void
    {
        foreach ($this->templates as $item) {
            if (empty($this->template)) {
                $this->template = $this->getTemplate($item['template']);
            }
            
            $this->replaceHolderWithContent($item['desteny'], $item['template']);
            
            $this->css .= $this->getDependencies('css', CSS_PATH, $item['template']);
            
            $this->js .= $this->getDependencies('js', JS_PATH, $item['template']);
        }
    }
    
    private function replaceHolderWithContent(
        string $holder, 
        string $name, 
        string $content = null
    ): void {
        if (!$this->searchSubTemplateHolder($holder)) {
            return;
        }
        
        if (is_null($content)) {
            $content = $this->getTemplate($name);
        }
        
        $this->template = str_replace(
            '[[' . strtoupper($holder) . ']]',
            $content, 
            $this->template
        );
    }

    private function genFilePath(string $name): string
    {
        $templateFullNameAndPath = str_replace('_', '/', $name);
        
        return FileManager::searchFileInDirAndSudDirAndReturnFile(
           TEMPLATE_PATH, 
           $templateFullNameAndPath
        );
    }
    
    private function transformData(string $returnedType, $data)
    {
        return (new DataFormater($returnedType, $data))->format();
    }
    
    private function searchSubTemplateHolder(string $holder): bool
    {
        $found = strpos($this->template, '[[' . strtoupper($holder) . ']]');
        return ($found === false) ? false : true;
    }
    
    private function searchSubTemplateData($subTemplateName)
    {
        if (!isset($this->data->$subTemplateName)) {
            return $this->data;
        }
        
        return $this->data;
    }
    
    private function setResource(AbstractResource $resource, string $name, string $extension)
    {
        return $resource->load($name, $extension);  
    }
    
    private function getDependencies(string $type, string $path, string $name)
    {
        if (!$this->searchSubTemplateHolder(strtoupper($type))) {
            return;
        }
        
        $class = '\\System\\Resource\\' . ucfirst($type) . 'Resource';
        
        return $this->setResource(new $class($path), $name, ".{$type}");
    }

}
