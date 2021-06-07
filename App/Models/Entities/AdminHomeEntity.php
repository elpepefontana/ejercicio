<?php

namespace App\Models\Entities;

use System\Helpers\UserInputHelper as InputHelper;

class AdminHomeEntity extends \System\Core\AbstractEntity {
    
    public $input;
    
    public function __construct(InputHelper $input) {
        parent::__construct($input); 
    }
}
