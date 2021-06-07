<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface as ControlInterface;

class ControlContext
{
    private $strategy;
    
    public function set(ControlInterface $strategy)
    {
        $this->strategy = $strategy;
    }
    
    public function render()
    {
        return $this->strategy->render();
    }
    
}
