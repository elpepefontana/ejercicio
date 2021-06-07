<?php

namespace System\Factories;

use System\Core\AbstractModel as AbstractModel;

interface ModelFactoryInterface
{
    public function create($object, $action, $type, $data, $session): ?AbstractModel;
}
