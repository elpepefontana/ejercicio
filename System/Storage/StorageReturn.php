<?php

namespace System\Storage;

class StorageReturn
{
    private $return;
    
    public function __construct(StorageReturnInterface $return)
    {
        $this->return = $return;
    }
    
    public function retrieve()
    {
        return $this->return->retrieve();
    }
}
