<?php

namespace System\Core;

use System\Core\FileManager as FileManager;

class Session 
{
    
    public function init()
    { 
        session_start();
    }
    
    public function add($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    public function get($key)
    {
        return !empty($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
    
    public function getAll()
    {
        return $_SESSION;
    }
    
    public function remove($key)
    {
        if (!empty($_SESSION[$key]))
            unset($_SESSION[$key]);
    }
    
    public function close()
    {
        session_unset();
        session_destroy();
    } 
    
    public function getStatus()
    { 
        return session_status();   
    }
    
    public function isStarted()
    {
        if (php_sapi_name() === 'cli') {
            return false;
        }
        if (!version_compare(phpversion(), '5.4.0', '>=')) {
            return empty(session_id()) ? false : true;
        }
        return session_status() === PHP_SESSION_ACTIVE ? true : false;
    }

    public function setImagesAccess($user_ip, $user_id) 
    {
        if ($this->isStarted() && filter_var($user_ip, FILTER_VALIDATE_IP) && IMAGES_ACCESS) {
            $newContent = "allow from {$user_ip} #{$user_id}\n";
            return FileManager::apendContentToFile(ROOT . HOME . "/Public/images/Gallery/", ".htaccess", $newContent);
        }
    }
    
}
