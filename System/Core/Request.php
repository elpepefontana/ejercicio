<?php

namespace System\Core;

class Response
{
    
    private $get;
    private $post;
    private $cookie;
    private $files;
    
    public function __contruct(array $get, array $post, array $cookie, array $files)
    {
        
    }
    
    public function add($target, $name, $value)
    {
        if($target === 'get'){
            $this->get = array_push($name, $value);
        }
        if($target === 'post'){
            
        }
    }
    
    
    
}
