<?php

namespace System\Renderer\InputRenderer;

use System\Renderer\InputRenderer\InputInterface as InputInterface;

class InputContext
{
    private $strategy;
    
    public function set(InputInterface $strategy)
    {
        $this->strategy = $strategy;
    }
    
    public function render()
    {
        return $this->strategy->render();
    }
    
    public function data()
    {
        return $this->strategy->data();
    }
    
}
