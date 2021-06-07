<?php

namespace System\Core;

use System\Storage\DataBase as Database;
use System\Core\Session as Session;

class Menu 
{  
    
    private $db; 
    
    public function __construct(Session $session) 
    {
        $queryBuilder = new \System\core\QueryBuilder();
        $this->storage = new Database($session, $queryBuilder);
    }
    
    public function genMenu($menuName)
    {
        $out = $this->searchMenu($menuName);  
        return json_encode($out);
    }
    
    public function searchMenu($menuName)
    {
        $sql = "SELECT * FROM `menu` WHERE `menutype` = 'menu' AND `menu` = 1 AND `name` = :name ORDER BY `orden`";
        $menu = $this->storage->executeQuery($sql, array(':name' => $menuName));
        if ($menu['Result'] !== 'OK' || $menu['TotalRecordCount'] == 0) { 
            return false; 
        }
        foreach ($menu['Records'] as $row) {
            $row['groups'] = $this->searchMenuGroups($row['id']);
            $rows[] = $row;
        }
        return isset($rows) ? $rows[0] : false;
    }
    
    public function searchMenuGroups($menuId) 
    {
        $sql   = "SELECT * FROM menu WHERE menutype = 'group' AND `menu` = 1 AND related = :menuId  ORDER by `orden`,`id`;";
        $group = $this->storage->executeQuery($sql, array(':menuId' => $menuId));
        if ($group['Result'] !== 'OK' || $group['TotalRecordCount'] == 0) { 
            return false; 
        }
        foreach ($group['Records'] as $row) {
            $row['items'] = $this->searchMenuItems($row['id']);
            $rows[] = $row;
        }
        return isset($rows) ? $rows : false;
    }
    
    public function searchMenuItems($menuGroup) 
    {
        $sql  = "SELECT * FROM menu WHERE menutype = 'item' AND `menu` = 1 AND related = :menuGroup ORDER by `orden`,`id`;";
        $item = $this->storage->executeQuery($sql, array(':menuGroup' => $menuGroup));
        if ($item['Result'] !== 'OK' || $item['TotalRecordCount'] == 0) { 
            return false;
        }
        return $item['Records'];
    }
}
