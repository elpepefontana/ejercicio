<?php

namespace System\Storage;

class StorageError implements StorageErrorInterface
{
    protected $error;
    protected $data = array();
    
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function data()
    {
        return array('Result' => 'ERROR', 'Message' => $this->data[0] . " - " . $this->error[2]);
    }
}
