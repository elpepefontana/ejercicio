<?php

namespace System\Core;

use System\Storage\DataBase as Database;
use System\Core\QueryBuilder as QueryBuilder;

class Home extends \System\Storage\Database
{   
    
    public function __construct(Session $session, QueryBuilder $qb) 
    {
        parent::__construct($session, $qb);
    }
    
    public function genHome($homeName) 
    {
        $out = $this->searchHome($homeName);  
        return json_encode($out);
    }
    
    public function searchHome($homeName)
    {
        $sql = "SELECT * FROM menu WHERE menutype = 'menu' AND `home` = 1 AND `name` = :homeName;";
        $result = $this->executeQuery($sql, array('homeName' => $homeName));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] == 0) {
            return false;
        }
        foreach ($result['Records'] as $row) {
            $row['groups'] = $this->searchHomeGroups($row['id']);
            $rows[] = $row;
        }
        return isset($rows) ? $rows[0] : false;
    }
    
    public function searchHomeGroups($homeId)
    {
        $sql = "SELECT * FROM menu WHERE `menutype` = 'group' AND `home` = 1 AND `related` = :homeId;";
        $result = $this->executeQuery($sql, array('homeId' => $homeId));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] == 0) {
            return false;
        }
        foreach ($result['Records'] as $row) {
            $row['items'] = $this->searchHomeItems($row['id']);
            $rows[] = $row;
        }
        return isset($rows) ? $rows : false;
    }
    
    public function searchHomeItems($homeGroup)
    {
        $sql = "SELECT * FROM menu WHERE menutype = 'item' AND `home` = 1 AND `related` = :homeGroup;";
        $result = $this->executeQuery($sql, array('homeGroup' => $homeGroup));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] == 0) {
            return false;
        }
        return $result['Records'];
    }
}
