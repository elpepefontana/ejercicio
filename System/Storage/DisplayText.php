<?php

namespace System\Storage;

class DisplayText implements StorageReturnInterface
{
    private $data;
    
    private $error;
    
    public function __construct(StorageErrorInterface $error, array $data) 
    {
        $this->error = $error;
        $this->data = $data;
    }
    
    public function retrieve(): array
    {
        if (!$this->data['result']) {
            return $this->error->data();
        }
        array_unshift($this->data['data'], array('DisplayText' => '(ninguno)', 'Value' => 'NULL'));
        return array(
            'Result' => 'OK',
            'Options' => $this->data['data'],
            'TotalRecordCount' => $this->data['count']
        );
    }

}
