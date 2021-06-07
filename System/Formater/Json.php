<?php

namespace System\Formater;

class Json implements FormatInterface
{
    private $data;
    
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function format()
    {
        if (is_object($this->data)) {
            return json_encode($this->data);
        }
        
        if (is_array($this->data)) {
            return json_encode($this->data);
        }
        
        $result = json_decode($this->data);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $this->data;
        }
        
        return json_encode($this->data);
    }
}