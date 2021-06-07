<?php

namespace System\Core;

use System\Helpers\UserInputHelper as InputHelper;
use System\Traits\UtilitiesTrait as UtilitiesTrait;

abstract class AbstractEntity 
{
    
    use UtilitiesTrait;
    
    protected $input;
    protected $message;

    public function __construct(InputHelper $input) 
    {
        $this->input = $input;
        $this->message = '';
    }

    // PRINCIPAL FUNCTIONS - FUNCIONES PRINCIPALES
    
    public function validate(string $className, array $params): array 
    {
        $this->map($className, $params);
        if (!empty($this->message)) { 
            return $this->message; 
        }
        return $this->getValidatedProperties($className);
    }
    
    public function map($className, array $values) 
    {
        $data = $this->getValidatedProperties($className, false);
        foreach ($data as $item) {
            $method = "set" . $this->toCamelCase($item, true);
            $value  = !empty($values[$item]) && isset($values[$item]) ? $values[$item] : '';
            method_exists($this, $method) ? $this->$method($value) : null;
        }
    }

    public function fieldError(string $name) 
    {
        $this->message .= "Verificar el dato {$name}, es erroneo.\n";
    }
    
    private function getValidatedProperties($className, $assoc = true): array 
    {
        $class   = $this->getClassNameOnly($className);
        $reflect = new \ReflectionClass($className);
        $props   = $reflect->getProperties();
        $out     = [];
        foreach ($props as $prop) {
            if (!strpos(strtolower($prop->class), strtolower($class))) {
                continue;
            }
            $field  = $prop->getName();
            $method = "get". $this->toCamelCase($field, true);
            $val    = method_exists($this, $method) ? $this->$method() : '';
            if (!$assoc) {
                $out[] = $field;
            } else {
                $out[$field] = $val;
            }
        }
        return $out;
    }
    
    private function getClassNameOnly(string $str): string 
    {
        $arr   = explode('\\', $str);
        $count = count($arr) - 1;
        return $arr[$count];
    }
    

}
    