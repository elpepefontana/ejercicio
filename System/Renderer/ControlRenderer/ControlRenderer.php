<?php

namespace System\Renderer\ControlRenderer;

use System\Core\Controls as Controls;

use System\Core\FileManager as FileManager;
use System\Renderer\RendererInterface as RendererInterface;

class ControlRenderer implements RendererInterface
{
    
    use \System\Traits\UtilitiesTrait;
    
    private $subTypeData;
    private $context;
    
    private $template;
    private $headers;
    private $main;
    private $section;
    private $article;
    private $content;
    
    private $factory;
    
    public function __construct()
    {
        $session = new \System\Core\Session();
        
        $this->factory = new \System\Factories\Factory();
        
        $this->template = $this->factory->create('model', 'template', '', 'codegen', '', $session);
        
        $this->headers = $this->factory->create('model', 'headers', '', 'codegen', '', $session);
        $this->main    = $this->factory->create('model', 'main', '', 'codegen', '', $session);
        $this->section = $this->factory->create('model', 'section', '', 'codegen', '', $session);
        $this->article = $this->factory->create('model', 'article', '', 'codegen', '', $session);
        $this->footer  = $this->factory->create('model', 'footer', '', 'codegen', '', $session);
        
        $this->content = $this->factory->create('model', 'content', '', 'codegen', '', $session);
        
        $this->context = new ControlContext();
        
        $this->setSubTypeData();
    }
    
    public function setSubTypeData()
    {
        if (!is_file(CONTROL_RENDERER_PATH . 'controlsubtypelist.json')) {
            $this->subTypeData = null;
            return;
        }
        $this->subTypeData = FileManager::readJsonFile(
            CONTROL_RENDERER_PATH, 
            'controlsubtypelist.json', 
            true
        );
    }
    
    public function setClassName($classSubtype): string
    {
        return '\\System\\Renderer\\ControlRenderer\\' . $this->getSubtype($classSubtype);
    }
    
    public function getSubtype($classSubtype): ?string
    {
        if (!array_key_exists($classSubtype, $this->subTypeData)) {
            return null;
        }
        
        return $this->subTypeData[$classSubtype];
    }
    
    public function searchTemplateNameById(int $id_template) 
    {
        return $this->template->searchTemplateNameById($id_template);
    }
    
    private function getContent($object, $id_object)
    {
        $result = $this->content->searchContent(
            array(
                'object' => $object,
                'id_object' => $id_object
            )
        );
        
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] === 0) {
            return array();
        }
        
        return $result['Records'];
    }
    
    public function setCompleteTemplateContent($id_template, $params)
    {
        $templateContent = $this->getContent('template', $id_template);
        if (empty($templateContent)) {
            return '';
        }
        
        return $this->createObjectAndDraw($templateContent, $params);
    }
    
    private function setTemplateContentBytemplateParts($id_template)
    {
        $headers = json_decode($this->headers->searchHeaders(array('id_template' => $id_template)), true);
        if (empty($headers)) {
            
        }
    }
    
    private function setHeadersContent($id_template)
    {
        $headers = json_decode($this->headers->searchHeaders(array('id_template' => $id_template)), true);
        if (empty($headers)) {
            return $this->getDefaultHeaders();
        }
        
        return $this->createObjectAndDraw($headers, $params);
    }
    
    private function getDefaultHeaders()
    {
        return '    require_once "../Public/template/headers/defaultheaders.php";\n';
    }
    
    private function setFooterContent($id_template)
    {
        $footer = $this->headers->searchHeaders(array('id_template' => $id_template));
        if (empty($footer)) {
            return $this->getDefaultFooter();
        }
        
        $footer = $this->getContent('footer', $main['id']);
    }
    
    private function getDefaultFooter()
    {
        return '    require_once "../Public/template/headers/defaultfooter.php";\n';
    }
    
    private function getMainContent($idTemplate)
    {
        $main = $this->main->searchMain(array('id_template' => $idTemplate));
        if (empty($main)) {
            return '';
        }
        
        $section = $this->getContent('main', $main['id']);
    }
    
    public function genSectionsAndArticles(int $id_template, array $params): string
    {
        $sections = $this->section->searchSection(array('object' => $params['object'], 'id_object' => $params['id_object']));
        if (empty($sections)) {
            return '';
        }
        $content  = '';
        foreach ($sections as $section) {
            $sectionContent = $this->genArticles($id_template, $section['id'], $section['name'], $params);
            $content .= Controls::drawSection("section_{$section['name']}", $section['css_class'], $sectionContent, $section['attributes']);
        }
        return $content;
    }
    
    private function genArticles(int $id_template, int $id_section, string $sectionName, array $params = null): string
    {
        $articles = $this->article->searchArticles($id_section);
        $cell = $this->sectionContent($sectionName, $id_template, $id_section, $articles, $params);
        if (empty($cell)) {
            $cell = $this->articleContent($id_template, $id_section, $articles, $articles['id'], $params);
        }
        $content = Controls::drawRow("row_{$sectionName}", '', $cell, '');
        return $content;
    }
    
    private function articleContent($id_template, $id_section, $articles, $articleId, $params)
    {
        if (empty($articles)) {
            return '';
        }
        $cell = '';
        foreach ($articles as $article) {
            $contents = $this->content->searchContentByTemplateSectionAndArticle($id_template, $id_section, $articleId);
            $articleContent .= !empty($contents) ? $this->createObjectAndRender($contents[0], $params) : '';
            $cellContent = Controls::drawArticle("art_{$article['name']}", $article['css_class'], $articleContent, $article['attributes']);
            $cell .= Controls::drawCell("cell_{$article['name']}", '', $cellContent, '', $article['width'], $article['offset']);
        }
        return $cell;
    }
    
    private function sectionContent($sectionName, $id_template, $id_section, $articles, $params)
    {
        if (!empty($articles)) {
            return '';
        }
        $contents = $this->content->searchContentByTemplateSectionAndArticle($id_template, $id_section, '');
        $cellContent = !empty($contents) ? $this->createObjectAndRender($contents[0], $params) : '';
        return Controls::drawCell("cell_{$sectionName}", '', $cellContent, '', 12, '');
    }
    
    private function createObjectAndRender(array $data, array $params): string
    {
        $className = $this->setClassName($data['type']);
        if (empty($className)) {
            return '';
        }
        
        $this->context->set(
            new $className(
                $data, 
                $params
            )
        );
        
        return $this->context->render();
    }
}