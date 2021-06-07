<?php

namespace System\Renderer;

interface RendererInterface
{
    public function setClassName($classSubtype): string; 
    
    public function getSubtype($classSubtype): ?string;
    
    public function setSubTypeData();
}
