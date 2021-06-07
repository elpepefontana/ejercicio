<?php

namespace System\Formater;

class Obj implements FormatInterface
{
    private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function format()
    {
        if (is_object($this->data)) {
            return $this->data;
        }
        
        if (is_array($this->data)) {
            return (object) $this->data;
        }
        
        $result = json_decode($this->data);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $result;
        }
        
        return (object) $this->data;
    }
}