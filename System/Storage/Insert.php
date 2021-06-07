<?php

namespace System\Storage;

class Insert implements StorageReturnInterface
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
        
        return array(
            'Result' => 'OK',
            'TotalRecordCount' => 1,
            'Id' => $this->data['id']
        );
    }
}
