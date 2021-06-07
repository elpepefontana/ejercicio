<?php 

namespace App\Models\Entities;

use System\Helpers\UserInputHelper as InputHelper;

defined('BASEPATH') or exit('No se permite acceso directo');

class GroupEntity  extends \System\Core\AbstractEntity
{
    protected $id;
    protected $id_users;
    protected $id_groups;

    public function __construct(InputHelper $input)
    {
        parent::__construct($input);
    }

    public function setId($value)
    {
        $valid    = $this->input->integerValidation($value);
        $result   = $valid !== false ? $this->input->integerSanitation($valid) : '';
        $this->id = $result;
    }

    public function setName($value)
    {
        $valid  = $this->input->stringSanitation($value);
        $result = !empty($valid) ? $valid : '';
        $this->name = $result;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

}
