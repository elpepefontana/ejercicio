<?php

namespace System\Storage;

class Select implements StorageReturnInterface
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
        
        return array (
            'Result' => 'OK',
            'Records' => $this->data['data'],
            'TotalRecordCount' => $this->data['count']
        );
    }
}
