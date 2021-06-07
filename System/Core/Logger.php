<?php

namespace System\Core;

class Logger extends \System\Storage\Database
{
    
    protected $id;
    protected $id_client;
    protected $id_user;
    protected $related_object;
    protected $created;
    protected $action;
    protected $result;
    protected $description;
    
    public function __construct(Session $session, QueryBuilder $qb) 
    {
        parent::__construct($session, $qb);
    }
    
    public function setId($value)
    {
        $this->id = $value;
    }
    
    public function setIdClient($value)
    {
        $this->id_client = $value;
    }
    
    public function setIdUser($value)
    {
        $this->id_user = $value;
    }
    
    public function setRelatedObject($value)
    {
        $this->related_object = $value;
    }
    
    public function setCreated($value)
    {
        $this->created = $value;
    }
    
    public function setAction($value)
    {
        $this->action = $value;
    }
    
    public function setResult($value)
    {
        $this->result = $value;
    }
    
    public function setDescription($value)
    {
        $this->description = $value;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getIdClient()
    {
        return $this->id_client;
    }
    
    public function getIdUser()
    {
        return $this->id_user;
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
    
    
    public function externalDataInsert($id_client, $related_object, $action, $result, $description = '', $user)
    {
	$sql = "INSERT INTO log 
                (
                    id_client,
                    related_object,
                    created,
                    action,
                    result,
                    description,
                    id_user
                )
                VALUES 
                (
                    '{$id_client}',
                    '{$related_object}',
                    NOW(),
                    '{$action}',
                    '{$result}',
                    '{$description}',
                    '{$user}'
                );";
        //echo $id_client. " | " . $related_object. " | " . $action. " | " . $result. " | " . $description. " | " . $user;
        $sentence = $this->storage->prepare($sql);
        if (!$sentence->execute()) {
            return false;
        }
        return true;
    }
    
    public function searchLogger($params)
    {
        $data    = is_array($params) && count($params) > 0 ? $params : [];
        $limit   = array_key_exists('limit', $data) ? array_pop($data) : '';
        $result  = $this->model->select($this->model->getTableName(), array(), $data);
        $this->model->toJTableJson($result);
    }

    public function searchActiveLogger($params)
    {
        $data   = is_array($params) && count($params) > 0 ? $params : [];
        $limit  = array_key_exists('limit', $data) ? array_pop($data) : '';
        array_push($data, array('activated', 1));
        $total  = $this->model->select($this->model->getTableName(), array(), $data);
        if (!empty($limit)) {
            return $total;
        }
        $parcial = $this->model->select($this->model->getTableName(), array(), $data, $limit);
        $out     = array(
            'Result' => $parcial['Result'],
            'TotalRecordCount' => $total['TotalRecordCount'],
            'Records' => $parcial['Records']
        );
        $this->model->toJTableJson($out);
    }

    public function createLogger($params)
    {
        $result = $this->model->insert($this->model->getTableName(), $params);
        $this->model->toJTableJson($result, true);
    }

    public function changeLogger($params)
    {
        $this->model->toJTableJson($this->model->update($this->model->getTableName(), $params));
    }

    public function eraseLogger($params)
    {
        $data = array('id' => $params['id']);
        $this->model->toJTableJson($this->model->delete($this->model->getTableName(), $data));
    }

    public function getUniqueArrayLogger($params)
    {
        return array(
            'name' => $params['name'],
            'text' => $params['text']
        );
    }

    public function comboOptions($params)
    {
        $data = array('name' => $params['name']);
        $this->model->toJTableJson($this->model->selectOptions($this->model->getTableName(), $data));
    }
    
    public function comboSearchLogger($params)
    {
        $this->model->toJTableJson(
            $this->model->selectOptions(
                $this->model->getTableName(), 
                array(), 
                $params['extfield_name'], 
                'id'
            )
        );
    }
}
