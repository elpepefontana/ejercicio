<?php

namespace System\Resource;

use System\Resource\AbstractResource;

class CssResource extends AbstractResource
{
    
    public function __construct($path)
    {
        parent::__construct($path);
    }
    
    protected function setResourceTag($file)
    {
        if (!$this->validateResourceExistance($file)) {
            return;
        }
        
        $relative = strtolower(str_replace(ROOT, '', $file));
        
        return "<link rel=\"stylesheet\" href=\"{$relative}\" type=\"text/css\" />\n\n";
    }

}
