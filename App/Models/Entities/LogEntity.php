<?php 

namespace App\Models\Entities;

use System\Helpers\UserInputHelper as InputHelper;

defined('BASEPATH') or exit('No se permite acceso directo');

class LogEntity  extends \System\Core\AbstractEntity
{
    protected $id;
    protected $id_client;
    protected $related_object;
    protected $created;
    protected $action;
    protected $result;
    protected $description;
    protected $id_user;

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

    public function setIdClient($value)
    {
        $valid  = $this->input->integerSanitation($value);
        $result = !empty($valid) ? $valid : '0';
        $this->id_client = $result;
    }

    public function setRelatedObject($value)
    {
        $valid  = $this->input->stringSanitation($value);
        $result = !empty($valid) ? $valid : '';
        $this->related_object = $result;
    }

    public function setCreated($value)
    {
        $result = !empty($value) ? $value : 'NULL';
        $this->created = $result;
    }

    public function setAction($value)
    {
        $valid  = $this->input->stringSanitation($value);
        $result = !empty($valid) ? $valid : '';
        $this->action = $result;
    }

    public function setResult($value)
    {
        $valid  = $this->input->stringSanitation($value);
        $result = !empty($valid) ? $valid : '';
        $this->result = $result;
    }

    public function setDescription($value)
    {
        $valid  = $this->input->stringSanitation($value);
        $result = !empty($valid) ? $valid : '';
        $this->description = $result;
    }

    public function setIdUser($value)
    {
        $valid  = $this->input->integerSanitation($value);
        $result = !empty($valid) ? $valid : '0';
        $this->id_user = $result;
    }

    // GETTERS
    public function getId()
    {
        return $this->id;
    }

    public function getIdClient()
    {
        return $this->id_client;
    }

    public function getRelatedObject()
    {
        return $this->related_object;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getIdUser()
    {
        return $this->id_user;
    }

}
