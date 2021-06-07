<?php

namespace App\Controllers;

use System\Core\AbstractModel;
use System\Core\AbstractView;

abstract class AbstractAction
{
    use \System\Traits\UtilitiesTrait;
    
    protected $model;
    protected $view;
    protected $session;
    protected $ajax = false;
    public $data;
    
    public function __construct(
        AbstractModel $model, 
        ?AbstractView $view,
        $session,
        $data,
        bool $ajax = false
    ) {
        $this->model = $model;
        $this->view = $view;
        $this->session = $session;
        $this->data = $data;
        $this->ajax = $ajax;
    }
    
    abstract public function execute();
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function getData()
    {
        return $this->data;
    }
    
    public function getSpecificData(string $dataKey)
    {
        return isset($this->data[$dataKey]) ? $this->data[$dataKey] : null;
    }
    
    public function addData(string $key, $value, bool $overRide = false)
    {
        if (empty($key) && !empty($value)) {
            $this->addDataWithNoKey($key, $value, $overRide);
            return;
        }
        
        if (isset($this->data[$key])) {
            $this->data[$key] = !$overRide ? $this->data[$key] : $value;
            return;
        }
        
        $this->data[$key] = $value;
    }
    
    public function addDataWithNoKey(string $key, $value, bool $overRide = false): void
    {
        if (!empty($key)) {
            return;
        }
        
        if (is_string($value)) {
            $this->data[] = $value;
            return;
        }
        
        foreach($value as $identity => $data) {
            if (isset($this->data[$identity])) {
                $this->data[$identity] = !$overRide ? $this->data[$identity] : $data;
                return;
            }
            
            $this->data[$identity] = $data;
        }
    }
}
