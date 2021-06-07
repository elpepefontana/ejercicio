<?php

namespace System\Resource;
    
use System\Core\FileManager;

abstract class AbstractResource
{
    
    use \System\Traits\UtilitiesTrait;
    
    protected $type;
    protected $path;
    protected $name;
    
    public function __construct($path)
    {
        $this->path = $path;
    }

    public function load(string $name, string $extension)
    {
        $this->name = strpos($name, '_') === false ? $name : str_replace('_', '/', $name);
        
        if (is_dir($this->path . $this->name)) {
            return $this->getAllResourcesFromFolder($extension);
        }
        
        return $this->setResourceTag($this->path . $this->name . $extension);
    }
    
    abstract protected function setResourceTag(string $file);
    
    protected function getAllResourcesFromFolder($extension)
    {
        $cssFiles = FileManager::listFilesWithSpecifficExtensionInDir($this->path . $this->name, $extension);
        if (empty($cssFiles)) {
            return;
        }
        
        $content = '';
        foreach ($cssFiles as $file) {
            $content .= $this->setResourceTag($file);
        }
        
        return $content;
    }
    
    protected function validateResourceExistance($file)
    {
        return is_file($file);
    }
    
}
