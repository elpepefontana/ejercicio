<?php

namespace System\Core;

class Cookie
{

    public $options;
    
    public function __construct(array $options)
    {
        $this->options = $options;
        ob_start();
        register_shutdown_function(array(&$this, 'flushCookies'));
    }
    
    public function set($name = '', $value = '', $expiration = false) 
    {
        if (!empty($this->options['cookie_expire']))
            $expire = $expiration !== true ? '' : $this->options['cookie_expire'];
        else 
            $expire = $expitation !== true ? '' : strtotime('+30 days');
        $cookie = setcookie(
            $this->options['cookie_prefix'] . $name, 
            $value, 
            $expire, 
            $this->options['cookie_path'], 
            $this->options['cookie_domain'], 
            $this->options['cookie_secure'], 
            $this->options['cookie_httponly']
        );
        if ($cookie)
            $_COOKIE[$this->options['cookie_prefix'] . $name] = $value;
    }

    public function get(string $name = '') 
    {
        return $this->fetchFromArray($_COOKIE, $this->options['cookie_prefix'] . $name);
    }
    
    public function isCookie(string $name = '') 
    {
        return isset($_COOKIE[$this->options['cookie_prefix'] . $name]) ? true : false;
    }

    public function delete(string $name = '') 
    {
        $name = $this->options['cookie_prefix'] . $name;
        unset($_COOKIE[$name]);
        return $this->setCookie($name, '', true);
    }

    protected function fetchFromArray(&$array, $index = '')
    {
        if (isset($array[$index])) {
            $value = $array[$index];
        } elseif (($count = preg_match_all('/(?:^[^\[]+)|\[[^]]*\]/', $index, $matches)) > 1) {
            $value = $array;
            for ($i = 0; $i < $count; $i++) {
                $key = trim($matches[0][$i], '[]');
                if( $key === '') { break; }
                if(!isset($value[$key])) { return null; }
                $value = $value[$key];
            }
        } else
            return NULL;
        return $value;
    }

    public function flushCookies(){
        ob_flush();
    }

}