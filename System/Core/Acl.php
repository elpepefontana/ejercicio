<?php

namespace System\Core;

use System\Storage\Database as Database;
use System\Core\QueryBuilder as QueryBuilder;

class Acl extends Database
{
    protected $user;
    protected $userRole;
    protected $userGroup;
    protected $permits;
    protected $permissions;
    protected $groupRole;
    
     
    public function __construct(Session $session, QueryBuilder $qb) 
    {
        parent::__construct($session, $qb);
    }
    
    public function getAccessData(string $obj, $userId)
    {
        $sql = "SELECT
                    (SELECT name FROM permits WHERE id = p.id_permit) permit,
                    (
                        CASE
                            WHEN (
                                 SELECT count(*) 
                                 FROM permissions 
                                 WHERE related_object = p.obj 
                                     AND id_permit = p.id_permit 
                                     AND allow = 1
                                     AND id_role IN (
                                         SELECT id_role 
                                         FROM group_role 
                                         WHERE id_group IN (
                                            SELECT u.id_groups 
                                            FROM user_group u 
                                            WHERE u.id_users = :userId
                                         )
                                         UNION
                                         SELECT id_role 
                                         FROM user_role 
                                         WHERE id_users = :userId
                                    )
                            ) THEN 1 ELSE 0
                        END
                    ) allow
                FROM (
                    SELECT 
                        distinct p.related_object obj,
                        id_permit
                    FROM permissions p 
                    WHERE p.related_object = :obj 
                        AND p.id_role IN (
                            SELECT 
                                id_role 
                            FROM group_role 
                            WHERE id_group IN (
                                SELECT u.id_groups 
                                FROM user_group u 
                                WHERE u.id_users = :userId
                            )
                            UNION
                            SELECT id_role 
                            FROM user_role 
                            WHERE id_users = :userId
                        )   
                ) p"; 
        $result = $this->executeQuery($sql, array(':obj' => $obj, ':userId' => $userId));
        if ($result['TotalRecordCount'] === 0) {
            return array();
        }
        foreach ($result['Records'] as $row) {
            $aOut[$row['permit']] = $row['allow'];
        }
        return $this->validateAccessArray($aOut);
        
    }
    
    public function validateAccessArray(array $arr): array 
    {
        if (empty($arr)) {
            return $arr; 
        }
        $sql    = "SELECT name, 0 'allow' FROM permits;";
        $result = $this->executeQuery($sql, []);
        if($result['Result'] !== 'OK' || $result['TotalRecordCount']) {
            
        }
        foreach ($result['Records'] as $row) {
            $out[$row['name']] = $row['allow'];
        }
        $out['isAdmin'] = 0;
        return $out;
    }
    
    public function genAccess(string $obj, $userId): array 
    {
        if (!$this->isAdmin($userId)) { 
            return $this->getAccessData($obj, $userId); 
        }
        $sql = "SELECT `name`, 1 'allow' FROM `permits`;";
        $result = $this->executeQuery($sql, []);
        foreach ($result['Records'] as $row) {
            $out[$row['name']] = $row['allow'];
        }
        $out['isAdmin'] = 1;
        return $out;
    }   
    
    public function isAllowed(string $obj, string $role, string $permit): bool 
    {
        $sql = "SELECT `allow` 
                FROM `permissions` 
                WHERE `related_object` = :obj 
                    AND `id_role` = :role 
                    AND `id_permit` = :permit 
                    AND `allow` = 1;";
        $fields = array(':obj' => $obj, ':id_role' => $role, ':permit' => $permit);
        $result = $this->executeQuery($sql, $fields);
        return $result['TotalRecordCount'] > 0 ? true : false;
    }
    
    public function isAdmin($userId) : bool 
    {
        $rolesIds = $this->searchUserRoles($userId);
        foreach ($rolesIds as $aRole) {
            $roleId = $aRole['id_role'];
            if ($this->roleIsAdmin($roleId)) {
                return true;    
            }
        }
        return false;
    }
    
    public function roleIsAdmin(int $roleId): bool 
    {
        $sql = "SELECT * 
                FROM `role` 
                WHERE `id` = :roleId 
                    AND `int_name` = 'administrator';";
        $result = $this->executeQuery($sql, array(':roleId' => $roleId));
        return $result['TotalRecordCount'] > 0 ? true : false;
    }
    
    public function searchUserGroups(int $userId): array 
    {
        $sql = "SELECT `id_groups` 
                FROM `user_group` 
                WHERE `id_users` = :userId";
        $result = $this->executeQuery($sql, array(':userId' => $userId));
        return $result['Result'] === 'OK' ? $result['Records'] : [];
    }
    
    public function searchUserRoles($userId): array 
    {
        $sql = "SELECT `id_role` 
                FROM `group_role` 
                WHERE `id_group` IN (
                    SELECT u.`id_groups` 
                    FROM `user_group` u 
                    WHERE u.`id_users` = :userId
                )
                UNION
                    SELECT `id_role` 
                    FROM `user_role` 
                    WHERE `id_users` = :userId";
        $result = $this->executeQuery($sql, array(':userId' => $userId));
        return $result['TotalRecordCount'] > 0 ? $result['Records'] : [];
    }
 
}
