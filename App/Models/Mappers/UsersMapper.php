<?php 

namespace App\Models\Mappers;

use System\Core\AbstractMapper;
use System\Storage\StorageInterface as StorageInterface;
use System\Core\Session as Session;
use System\Core\QueryBuilder as QueryBuilder;

class UsersMapper extends AbstractMapper
{
    
    protected $session;

    public function __construct(StorageInterface $storage, Session $session, QueryBuilder $queryBuilder)
    {
        parent::__construct($storage, $session, $queryBuilder);
        $this->session = $session;
    }
    
    public function findAll($params)
    {
        $data   = count($params) > 0 ? $params : array();
        $order  = array_key_exists('sort', $data) ? array(urldecode(array_pop($data))) : [];
        $limit  = array_key_exists('limit', $data) ? array_pop($data) : '';
        return $this->search('users', array(), $data, $order, $limit);
    }
    
    public function findById($id)
    {
        $sub = $this->qb->sel('groups', '`name`');
        $sub .= $this->qb->where($this->qb->equal('id', 'u.id_groups'));
        
        $sql = $this->qb->sel('users', "u.*,({$sub}) groups", 'u');
        $sql .= $this->qb->where($this->qb->equal('id', $id, 'u'));
        
        $one = $this->storage->executeQuery($sql, array('id' => $id));
        
        return array(
            'Result' => $one['Result'],
            'Record' => $one['Records'][0]
        );
    }
    
    public function new($params)
    {
        $searched = array('last_name' => $params['last_name'], 'name' => $params['name']);
        $exists = $this->search('users', '*', $searched, array(), '');
        if ($exists['Result'] !== 'OK' || $exists['TotalRecordCount'] > 0) {
            return array('Result' => 'ERROR', 'Message' => 'Ya existen datos con esas características.');
        }
        
        $result = $this->storage->insert('users', $params);
        return $this->findById($result['Id']);
    }
    
    public function delete($params)
    {
        return parent::erase('users', $params);
    }
    
    public function modify($params)
    {
        return parent::change('users', $params);
    }

}
