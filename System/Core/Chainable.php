<?php

namespace System\Core;

class Chainable 
{
    private $instance = null;
    
    public function __construct()
    {
        $params = func_get_args();
        $this->instance = is_object($obj = array_shift($params)) ? $obj : new $obj($params);
    }

    public function __call($name, $params)
    {
        call_user_func_array([$this->instance, $name], $params);
        return $this;
    }
}