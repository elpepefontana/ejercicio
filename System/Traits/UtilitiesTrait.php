<?php

namespace System\Traits;

trait UtilitiesTrait 
{
    
    public function toCamelCase($value, $first = false)
    {
        $arr = !empty($value) && strpos($value, '_') !== false ? explode('_', $value) : false;
        if(!is_array($arr)){
             return ucfirst($value);
        }
        $out = '';
        foreach($arr as $str){
            if(empty($out))
                $out .= $first ? ucfirst($str) : $str;
            else
                $out .= ucfirst($str);
        }
        return $out;
    }
    
    public function debugFinalQuery($query, $params) 
    {
        $keys   = array();
        $values = $params;
        foreach ($params as $key => $value) {
            if (is_string($key)) {
                $keys[] = '/:'.$key.'/';
            } else {
                $keys[] = '/[?]/';
            }
            if (is_string($value))
                $values[$key] = "'" . $value . "'";
            if (is_array($value))
                $values[$key] = "'" . implode("','", $value) . "'";
            if (is_null($value))
                $values[$key] = 'NULL';
        }
        $query = preg_replace($keys, $values, $query);
        return $query;
    }
    
    public function debug($toPrint, $title = 'Detalle') 
    {
        echo"<pre>{$title}:<br>";
        var_dump($toPrint);
        echo "</pre>";
    }
    
    public function toJTableJson(array $data, bool $strip = false ) 
    {
        if (!is_array($data)) { 
            echo "{}"; 
            return;
        }
        $json = json_encode($data);
        echo $strip === true ? str_replace(array( '[',']' ), array( '','' ), $json) : $json;
    }
    
    
    public function isJSON($string) {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
    
}

