<?php

namespace System\Core;

class PathBuilder
{
    use \System\Traits\UtilitiesTrait;
    
    private $resource = 'controller';
    private $base;
    private $object;
    private $action;
    private $subFolder;
    private $extension = '.php';
    
    public $path = '';
    
    public function __construct(
        string $resource, 
        string $base, 
        string $object,
        string $action, 
        string $subFolder
    ) {
        $this->resource = !empty($resource) ? $resource : $this->resource;
        $this->base = $base;
        $this->object = $object;
        $this->action = $action;
        $this->subFolder = $subFolder;
    }
    
    public function build()
    {
        $this->path = "{$this->base}";
        $this->path .= !empty($this->subFolder) ? "{$this->subFolder}/" : '';
        $this->path .= $this->toCamelCase($this->object, true);
        if (strtolower($this->resource) === 'controller') {
            $this->path .= "/" . $this->toCamelCase($this->object);
        } else {
            $this->path .= "/" . $this->toCamelCase($this->action, true);
        }
        $this->path .= ucfirst(strtolower($this->resource));
        $this->path .= $this->extension;
        return $this->path;
    }
    
    public function toClass()
    {
        return str_replace($this->extension, '', str_replace('/', '\\', $this->path));
    }
    
    public function validatePathExistance()
    {
        return is_file($this->path);
    }
    
}
