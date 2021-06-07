<?php 

namespace App\Models\Mappers;

use System\Core\AbstractMapper;
use System\Storage\StorageInterface as StorageInterface;
use System\Core\Session as Session;
use System\Core\QueryBuilder as QueryBuilder;

class GroupMapper extends AbstractMapper
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
        return $this->search('groups', array(), $data, $order, $limit);
    }
    
    public function findById($id)
    {
        $sql = $this->qb->sel('groups', '*');
        $sql .= $this->qb->where($this->qb->equal('id', $id));
        $sql .= $this->qb->orderBy(array('name'));
        
        $one = $this->storage->executeQuery($sql, array('id' => $id));
        
        return array(
            'Result' => $one['Result'],
            'Record' => $one['Records'][0]
        );
    }
    
    public function options()
    {
        return $this->storage->selectOptions('groups', array(), 'name', 'id');
    }
    
    public function new($params)
    {
        $searched = array('name' => $params['name']);
        $exists = $this->search('groups', '*', $searched, array(), '');
        if ($exists['Result'] !== 'OK' || $exists['TotalRecordCount'] > 0) {
            return array('Result' => 'ERROR', 'Message' => 'Ya existen datos con esas características.');
        }
        
        $result = $this->storage->insert('groups', $params);
        return $this->findById($result['Id']);
    }
    
    public function delete($params)
    {
        return parent::erase('groups', $params);
    }
    
    public function modify($params)
    {
        return parent::change('groups', $params);
    }

}