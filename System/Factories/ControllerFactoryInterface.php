<?php

namespace System\Factories;

use System\Core\AbstractController as AbstractController;

interface ControllerFactoryInterface
{
    public function create($object, $action, $type, $data, $session): ?AbstractController;
}
