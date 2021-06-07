<?php

namespace System\Helpers;

defined('BASEPATH') or exit('No se permite acceso directo');

class CoreHelper 
{
    
    public static function validateController($controller)
    {
        return is_file($controller);
    }

    public static function validateAction($controller, $action)
    {
        if (strpos($action, '-') !== false) {
            return is_file(CONTROLLER_PATH . "{$controller}/" . self::setPathFolderStruture($action) . "Action.php");
        }
        return is_file(CONTROLLER_PATH . "{$controller}/{$action}Action.php");
    }
    
    private static function setPathFolderStruture($action)
    {
        $aAction = explode('-', $action);
        
        $path = '';
        foreach ($aAction as $action) {
            $path .= !empty($path) ? '/' . $action : self::toCamelCase($action, true);
        }
        
        return $path .'';
    }
    
    public static function toCamelCase($value, $first = false)
    {
        $arr = !empty($value) && strpos($value, '_') !== false ? explode('_', $value) : false;
        if (!is_array($arr)) {
             return ucfirst($value);
        }
        $out = '';
        foreach ($arr as $str) {
            if (empty($out)) {
                $out .= $first ? ucfirst($str) : $str;
            } else {
                $out .= ucfirst($str);
            }
        }
        return $out;
    }
}
