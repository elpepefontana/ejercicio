<?php

namespace System\Renderer\ControlRenderer;

class Secondary extends Heading
{
    public function __construct($typeData, $data)
    {
        parent::__construct($typeData, $data);
        parent::addData('size', 1);
        parent::render();
    }
}
