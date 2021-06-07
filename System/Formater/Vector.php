<?php

namespace System\Formater;

class Vector implements FormatInterface
{
    private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function format()
    {
        if (is_array($this->data)) {
            return $this->data;
        }
        
        if (is_object($this->data)) {
            return (array) $this->data;
        }
        
        $result = json_decode($this->data, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $result;
        }
        return $this->data;
    }
}