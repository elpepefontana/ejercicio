<?php

namespace System\Formater;

use System\Formater\FormatInterface as FormatInterface;

class DataFormater implements FormatInterface
{
    private $formatType;
    private $formater = null;
    private $toPrint;
    
    public function __construct($formaType, $data, $toPrint = false)
    {
        $this->formatType = $formaType;
        $this->toPrint = $toPrint;
        $className = "\\System\\Formater\\" . $this->formatType;
        $this->create(new $className($data));
    }
    
    private function create(FormatInterface $formater)
    {
        $this->formater = $formater;
    }
    
    public function format()
    { 
        if (is_null($this->formater)) {
            return null;
        }
        
        if ($this->toPrint) {
            echo $this->formater->format();
            return;
        }
                
        return $this->formater->format();
    }
}
