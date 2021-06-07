<?php 

namespace App\Models\Entities;

use System\Helpers\UserInputHelper as InputHelper;

defined('BASEPATH') or exit('No se permite acceso directo');

class UsersEntity  extends \System\Core\AbstractEntity
{
    protected $id;
    protected $id_groups;
    protected $name;
    protected $last_name;
    protected $email;
    protected $observation;

    public function __construct(InputHelper $input)
    {
        parent::__construct($input);
    }

    // SETTERS
    public function setId($value)
    {
        $valid    = $this->input->integerValidation($value);
        $result   = $valid !== false ? $this->input->integerSanitation($valid) : '';
        $this->id = $result;
    }
    
    public function setIdGroup($value)
    {
        $valid    = $this->input->integerValidation($value);
        $result   = $valid !== false ? $this->input->integerSanitation($valid) : '';
        $this->id_groups = $result;
    }

    public function setName($value)
    {
        $valid  = $this->input->stringSanitation($value);
        $result = !empty($valid) ? $valid : '';
        $this->name = $result;
    }

    public function setLastName($value)
    {
        $valid  = $this->input->stringSanitation($value);
        $result = !empty($valid) ? $valid : '';
        $this->last_name = $result;
    }
    
    public function setEmail($value)
    {
        $valid  = $this->input->stringSanitation($value);
        $result = !empty($valid) ? $valid : '';
        $this->email = $result;
    }
    
    public function setObservation($value)
    {
        $valid  = $this->input->stringSanitation($value);
        $result = !empty($valid) ? $valid : '';
        $this->observation = $result;
    }

    // GETTERS
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

}
