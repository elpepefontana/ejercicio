<?php

namespace System\Core;

class QueryBuilder 
{
    use \System\Traits\UtilitiesTrait;

    protected $placeHolder;
    protected $defPlaceHolder;

    public function __construct() 
    {
        $this->setDefPlaceHolder(true);
        $this->setPlaceHolder();
    }

    public function setPlaceHolder() 
    {
        $this->placeHolder = $this->getDefPlaceHolder() ? ':' : '?';
    }
     
    public function setDefPlaceHolder($value) 
    {
        $this->defPlaceHolder = $value;
    }
    
    public function getPlaceHolder() 
    {
        return $this->placeHolder;
    }

    public function getDefPlaceHolder() 
    {
        return $this->defPlaceHolder;
    }

    public function sel(string $tableName, string $fields, string $alias = ''): string 
    {
         return "SELECT {$fields} FROM `{$tableName}` {$alias} ";
    }

    public function ins(string $tableName, string $fields, string $values): string 
    {
        return "INSERT INTO `{$tableName}` ({$fields}) VALUES ({$values});";
    }

    public function upd(string $tableName): string 
    {
        return "UPDATE `{$tableName}`";
    }

    public function del(string $tableName): string  
    {
        return "DELETE FROM `{$tableName}`";
    }

    public function where(string $clause): string  
    {
        return " WHERE {$clause}";
    }

    public function addAnd($clause) 
    {
        return " AND {$clause} ";
    }
    
    public function addOr($clause)
    {
        return " OR {$clause} ";
    }
    
    public function logicalParentesis($clause)
    {
        return "({$clause})";
    }

    public function createWhereClause(array $data, string $dbAlias = ''): string 
    {
        if (count($data) == 0) {
            return '';
        }
        $out   = "";
        $alias = !empty($dbAlias) ? $dbAlias . "." : '';
        foreach ($data as $key => $value) {
            $val = strtolower($key);
            $out .= empty($out) ? $alias . $this->equal($val) : $this->addAnd($alias . $this->equal($val));
        }
        return $this->where($out);
    }

    public function createUpdateClause(array $data): string 
    {
        if (count($data) == 0) {
            return '';
        }
        $out = "";
        foreach ($data as $key => $value) {
            $val = strtolower($key);
            if ($key !== 'id') {
                $out .= empty($out) ? $this->addUpdateClause($val) : ",\n {$this->addUpdateClause($val)}";
            }
        }
        return " SET {$out} ";
    }
    
    public function createUpdateNoEmptyClause(array $data): string 
    {
        if (count($data) == 0) {
            return '';
        }
        $out = "";
        foreach ($data as $key => $value) {
            $val = strtolower($key);
            if ($key !== 'id' && !empty($val)) {
                $out .= empty($out) ? $this->addUpdateClause($val) : ",\n {$this->addUpdateClause($val)}";
            }
        }
        return " SET {$out}";
    }
    
    public function orderBy(array $data): string 
    {
        $order = 'ASC';
        $out   = "";
        foreach ($data as $val) {
            $value = $val;
            if (strpos($val, ' ') !== false) {
                list($value, $order) = explode(' ', $val);
            }  
            $lowerValue = strtolower($value);
            $out .= empty($out) ? " `{$lowerValue}` {$order}" : ", `{$lowerValue}` {$order}";
        }
        return !empty($out) ? " ORDER BY {$out}" : '';
    }
    
    public function orderByRand(string $returnedRows)
    {
        $limit = !empty($returnedRows) ? $this->limit($returnedRows) : '';
        return !empty($limit) ? "ORDER BY rand() {$limit}" : "ORDER BY rand()";
    }
    
    public function limit($data): string 
    {
        if (is_array($data) && count($data) > 0) { 
            $out = implode(',', $data); 
        }
        if (is_string($data)) { 
            $out = $data; 
        }
        return !empty($out) ? " LIMIT {$out}" : "";
    }
    
    public function subQuery(string $sql, string $fieldAlias): string 
    {
        $alias = !empty($fieldAlias) ? $fieldAlias : '';
        return "({$sql}) {$alias}";
    }

    public function addField(string $field, string $fieldAlias = '', string $tableAlias = ''): string 
    {
        $tableAlias = !empty($tableAlias) ? $tableAlias . '.' : '';
        return !empty($field) ? "{$tableAlias}`" . $field . '` ' . $fieldAlias  : "*";
    }
 
    public function addFields(array $fields, string $tableAlias = ''): string 
    {
        $tableAlias = !empty($tableAlias) ? $tableAlias . '.' : '';
        return !empty($fields) ? "{$tableAlias}`" . implode("`, `{$tableAlias}", $fields) . '`' : "*";
    }

    public function addFieldsWithValue(array $data, string $tableAlias = ''): string
    {
        $out = "";
        $tableAlias = !empty($tableAlias) ? $tableAlias . '.' : '';
        foreach ($data as $key => $val) {
            if ($val === '') {
                continue;
            }
            $out .= !empty($out) ? ", {$tableAlias}`{$key}`" : "{$tableAlias}`{$key}`";
        }
        return $out;
    }

    public function addFieldsWithAlias(array $fields, array $alias): string 
    {
        $out = "";
        if (count($fields) !== count($alias)) {
            return '';
        }
        for ($a = 0; $a < $fields; $a++) {
            $out = empty($out) ? "`" . $fields[$a] . "` " . $alias[$a] : ",`" . $fields[$a] . "` " . $alias[$a];
        }
        return !empty($out) ? $out : $this->addFields($fields);
    }

    public function insertHolders(array $data): string 
    {
        $out = "";
        foreach ($data as $key => $val) {
            if ($val === '') {
                continue;
            }
            $holder = $this->defPlaceHolder ? $this->placeHolder . "{$key}" : $this->placeHolder;
            $out .= !empty($out) ? ", " . $holder : $holder;
        }
        return $out;
    }

    public function addUpdateClause(string $field): string 
    {
        $holder = $this->placeHolderDecide($field);
        return " `{$field}` = {$holder}";
    }

    public function addValues(array $data): array 
    {
        $out = [];
        foreach ($data as $key => $val) {
            if ($this->defPlaceHolder)
                $out[$key] = $val !== 'NULL' ? $val : null;
            else
                $out[] = $val !== 'NULL' ? $val : null;
        }
        return $out;
    }

    public function addNonEmptyValues(array $data): array 
    {
        $out = [];
        foreach ($data as $key => $val) {
            if ($val === '') {
                continue;
            }
            if ($this->defPlaceHolder) {
                $out[$key] = $val !== 'NULL' ? $val : null;
            } else {
                $out[] = $val !== 'NULL' ? $val : null;
            }
        }
        return $out;
    }

    public function equal(string $field, string $fixedSearch = '', string $alias = ''): string 
    {
        $holder = $this->placeHolderDecide($field);
        if (empty($fixedSearch)) {
            return empty($alias) ? "`{$field}` = {$holder} " : "{$alias}.`{$field}` = {$holder} ";
        }
        if(strpos($fixedSearch, ':') === false && strpos($fixedSearch, '.') === false && strpos($fixedSearch, '(') === false)
            $search = is_numeric($fixedSearch) ? $fixedSearch : "'{$fixedSearch}'";
        else 
            $search = $fixedSearch;
        return empty($alias) ? "`{$field}` = {$search} " : "{$alias}.`{$field}` = {$search} ";
    }
    
    public function isIn(string $field, $search): string
    {
        if (!is_array($search) &&  is_numeric($search)) {
            return  "`{$field}` IN ({$search}) "; //" ";
        } elseif (!is_array($search) &&  !is_numeric($search)) {
            return "`{$field}` IN ({$search})";
        }   
        $out = '';
        foreach ($search as $val) {
            $coma = empty($out) ? '' : ',';
            if (is_numeric($val))
                $out .= $coma . $val;
            else
                $out .= $coma . "'{$val}'";
        }
        return " `{$field}` IN ({$out}) ";
    }
    
    public function isNotIn(string $field, $search): string
    {
        if (!is_array($search) &&  is_numeric($search)) {
            return  "`{$field}` NOT IN ({$search}) "; //" ";
        } elseif (!is_array($search) &&  !is_numeric($search)) {
            return "`{$field}` NOT IN ({$search})";
        }   
        $out = '';
        foreach ($search as $val) {
            $coma = empty($out) ? '' : ',';
            if (is_numeric($val))
                $out .= $coma . $val;
            else
                $out .= $coma . "'{$val}'";
        }
        return " `{$field}` NOT IN ({$out}) ";
    }
    
    public function distinct(string $field, string $fixedSearch): string 
    {
        if (empty($fixedSearch)) {
            return " `{$field}` <> {$this->placeHolderDecide($field)} ";
        }
        return is_numeric($fixedSearch) ? " `{$field}` <> $fixedSearch" : " `{$field}` <> '$fixedSearch'";
    }

    public function contains(string $field, string $fixedValue): string 
    {
        $likeCompareValue = !empty($fixedValue) ? $fixedValue : $this->placeHolderDecide($field);
        return " `{$field}` LIKE CONCAT('%'" . $likeCompareValue . ",'%') ";
    }

    public function begins($field, string $fixedValue): string 
    {
        $likeCompareValue = !empty($fixedValue) ? $fixedValue : $this->placeHolderDecide($field);
        return " `{$field}` LIKE CONCAT(" . $likeCompareValue . ",'%') ";
    }

    public function ends($field, string $fixedValue): string 
    {
        $likeCompareValue = !empty($fixedValue) ? $fixedValue : $this->placeHolderDecide($field);
        return " `{$field}` LIKE CONCAT('%', " . $likeCompareValue . ") ";
    }

    public function mayor($field): string 
    {
        return " `{$field}` > " . $this->placeHolderDecide($field) . " ";
    }

    public function minor($field): string 
    {
        return " `{$field}` < " . $this->placeHolderDecide($field) . " ";
    }

    public function mayorAndEqual($field): string 
    {
        return " `{$field}` >= " . $this->placeHolderDecide($field) . " ";
    }

    public function minorAndEqual($field): string 
    {
        return " `{$field}` <= " . $this->placeHolderDecide($field) . " ";
    }
    
    private function placeHolderDecide($field)
    {
        return ($this->getDefPlaceHolder()) ? $this->getPlaceHolder() . $field : $this->getPlaceHolder();
    }
    
    public function join(
        string $rightTableName,
        string $rightTableAlias, 
        string $keyField,
        string $leftTableAlias
    ): string {
        return " {$leftTableAlias}\n JOIN `{$rightTableName}` {$rightTableAlias}\n\t 
            ON {$leftTableAlias}.`id` = {$rightTableAlias}.`{$keyField}` ";
    }

}
