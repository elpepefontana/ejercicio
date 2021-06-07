<?php 

namespace App\Models\Entities;

use System\Helpers\UserInputHelper as InputHelper;

defined('BASEPATH') or exit('No se permite acceso directo');

class ErrorPageEntity  extends \System\Core\AbstractEntity
{
    public function __construct(InputHelper $input)
    {
        parent::__construct($input);
    }
}
