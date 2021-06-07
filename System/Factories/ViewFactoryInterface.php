<?php

namespace System\Factories;

use System\Core\AbstractView as AbstractView;

interface ViewFactoryInterface
{
    public function create($object, $action, $type, $data, $session): AbstractView;
}
