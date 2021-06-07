<?php

namespace System\Resource;

use System\Resource\AbstractResource;

class JsResource extends AbstractResource
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
        
        return "<script type='text/javascript' src='{$relative}' defer></script>\n\n";
    }

}