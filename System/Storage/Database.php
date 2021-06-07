<?php

namespace System\Storage;

use PDO;
use System\Core\Session;
use System\Core\QueryBuilder;

class Database implements \System\Storage\StorageInterface
{
    
    use \System\Traits\UtilitiesTrait;

    protected $db;
    private $qb;
    protected $session;

    public function __construct(Session $session, QueryBuilder $queryBuilder) 
    {
        $this->validateConnection();
        $this->session = $session;
        $this->qb = $queryBuilder;
    }

    public function validateConnection() 
    {
        if ($this->db !== null) {
            return $this->db;
        }
        $this->db = new PDO(DB_DSN, DB_USER, DB_PASS);
    }
    
    public function executeQuery(string $sql, array $values = []): array 
    {
        $sentence = $this->db->prepare($sql);
        $result = $sentence->execute($values);
        
        $this->debugQueryAndValues($result, $sql, $values);
        $idEval = isset($values['id']) ? $values['id'] : '';
        
        $params = $this->setParamsData(
            $result, 
            $sentence->fetchAll(PDO::FETCH_ASSOC), 
            $sentence->rowCount(), 
            $this->db->errorInfo(), 
            $this->setId($idEval, $this->db->lastInsertId())
        );
        
        $className = '\\System\\Storage\\' . $this->getQueryType($sql);
        
        return $this->createReturnSubClassAndRetrieve($className, $params);
    }
    
    private function createReturnSubClassAndRetrieve($className, $params)
    {
        $return = new StorageReturn(
            new $className(
                new StorageError(
                    $params['error']
                ), 
                $params)
        );
        
        return $return->retrieve();
    }
    
    private function debugQueryAndValues(bool $queryResult, string $sql, array $values)
    {
        if ($queryResult || !DEBUG_QUERYS) {
            return;
        }
        
        $this->debug(__CLASS__);
        $this->debug($values);
        echo $this->debugFinalQuery($sql, $values);
    }

    public function select(
            string $tableName, 
            $fields, 
            array $data, 
            array $order, 
            $limit = null
    ): array {
        if (empty($fields)) {
            $fields = '*';
        }
        $fields = is_array($fields) ? $this->qb->addFields($fields, 'tb') : $fields; 
        
        $where = $this->qb->createWhereClause($data, 'tb');
        $validOrder = $this->qb->orderBy($order);
        $validLimit = $this->qb->limit($limit);
        $sql = $this->qb->sel($tableName, $fields, 'tb') . " {$where} {$validOrder} {$validLimit};";
        
        $values = $this->qb->addValues($data);
        return $this->executeQuery($sql, $values);
    }

    public function selectOptions(
        string $tableName, 
        array  $data, 
        string $nameField = null, 
        string $valueField = null,
        bool $emptyOption = false,
        bool $isNumeric = false          
    ): array {
        $name   = !empty($nameField) ? $nameField : 'text';
        $value  = !empty($valueField) ? $valueField : 'value';
        $fields = "`{$name}` DisplayText, `{$value}` \"Value\"";
        $comboOrder = !empty($nameField) ? $nameField : 'combo_order';
        
        $where  = $this->qb->createWhereClause($data);
        $order  = $this->qb->orderBy(array($comboOrder));
        $sql    = $this->qb->sel($tableName, $fields) . $where . $order;
        
        $values = $this->qb->addValues($data);
        
        return $this->executeQuery($sql, $values);
    }

    public function insert(string $tableName, array $data): array 
    {
        $fields  = $this->qb->addFieldsWithValue($data);
        $holders = $this->qb->insertHolders($data);
        $sql     = $this->qb->ins($tableName, $fields, $holders);
        
        $values  = $this->qb->addNonEmptyValues($data);
        return $this->executeQuery($sql, $values);
    }

    public function update(string $tableName, array $data): array 
    {
        $set   = $this->qb->createUpdateClause($data);
        $where = $this->qb->createWhereClause(array('id' => $data['id']));
        $sql   = $this->qb->upd($tableName) . " {$set} {$where};";
        
        $values = $this->qb->addNonEmptyValues($data);
        return $this->executeQuery($sql, $values);
    }

    public function delete(string $tableName, array $data): array 
    {
        $where  = $this->qb->createWhereClause($data);
        if (empty($where)) {
            return array();
        }
        $sql = $this->qb->del($tableName) . " {$where};";
        
        $values = $this->qb->addValues($data);
        return $this->executeQuery($sql, $values);
    }
    
    private function setId($scriptId, $lastInsertedId)
    {
        if (empty($scriptId)) {
            return $lastInsertedId;
        }
        return $scriptId;
    }
    
    private function setParamsData($result, $data, $count, $error, $id): array
    {
        return array(
            'result' => $result, 
            'data'   => !empty($data) && count($data) > 0 ? $data : [], 
            'count'  => !empty($count) ? $count : 0, 
            'error'  => !empty($error) && count($error) > 0 ? $error : [],
            'id'     => !empty($id) ? $id : 0
        );
    }
    
    private function getQueryType($sql)
    {
        $aTypes = explode(',', "DisplayText,SELECT,INSERT,UPDATE,DELETE");
        $out = '';
        foreach ($aTypes as $type) {
            if (strpos($sql, $type) === false) {
                continue;
            }
            $out = $type != 'DisplayText' ? ucfirst(strtolower($type)) : 'DisplayText';
            break;
        }
        $out = empty($out) ? 'Common' : $out;
        return $out;
    }
    
    public function addFormatedFieldByType(string $tableName, string $tableAlias = ''): string 
    {
        $fields = $this->setFieldsSearch($tableName);
        $out = "";
        $validAlias = !empty($tableAlias) ? $tableAlias . '.' : '';
        foreach ($fields as $row) {
            $out .= !empty($out) ? ',' : '';
            $out .= $this->setFieldsFormatAndSubQuerys($validAlias, $row);
        }
        return $out;
    }
    
    public function addFormatedFieldByTypeWithLanguagueCheck(string $tableName, string $tableAlias = ''): string 
    {
        $out = "";
        $validAlias = !empty($tableAlias) ? $tableAlias . '.' : '';
        $data = $this->setFieldsSearch($tableName);
        foreach ($data as $row) {
            $out .= !empty($out) ? ',' : '';
            if ($row['traducir'] == 1) {
                $out .= $this->setFieldsFormatAndSubQuerys($validAlias, $row);
            } else {
                $out .= $this->setSpecificTranslation($tableName, $row);
            }
        }
        return $out;
    }
    
    private function setSpecificTranslation(string $tableName, array $row): string 
    {
        $factory = new \System\Factories\Factory();
        $translation = $factory->create('model', 'translation',  '', 'codegen', '', $session);
        $objectName = $this->toCamelCase($tableName, true);
        
        $result = $translation->searchSpecificTranslation(
            $objectName, 
            $row['id'], 
            $row['nombre'], 
            $this->session->get('language')
        );
        
        if ($result['Result'] === 'OK' && !empty($result['Records'][0]['value'])) {
            $value = $result['Records'][0]['value'];
        }
        
        return " '{$value}' {$row['nombre']}";
    }
    
    private function setFieldsFormatAndSubQuerys(string $alias, array $row): string 
    {
        switch ($row['tipo']) {
            case 'DATE':
                return " DATE_FORMAT({$alias}`{$row['nombre']}`, '%Y-%m-%d') as {$row['nombre']}";
            case 'DATETIME':
                return " DATE_FORMAT({$alias}`{$row['nombre']}`, '%Y-%m-%d') as {$row['nombre']}";
            case 'ENUM':
                return $this->setEnumSubquery($row);
            case 'ENUMINT':
                return $this->setEnumIntSubquery($row);
            case 'ENUMDEPENDINT':
                return $this->setEnumDependIntSubquery($row);
            default:
                return "{$alias}`{$row['nombre']}`";
        } 
    }
    
    private function setEnumSubquery(array $fieldData): string
    {
        $sql  = "( " .$this->qb->sel('combo', 'text');
        $sql .= $this->qb->where($this->qb->equal('name', $fieldData['nombre']));
        $sql .= $this->qb->addAnd($this->qb->equal('value', "tb.{$fieldData['nombre']}"));
        $sql .= ") as {$fieldData['nombre']}";
        return $sql;
    }
    
    private function setEnumIntSubquery(array $fieldData): string
    {
        list($object, $field) = explode('|', $fieldData['externo']);
        $sql  = "( " .$this->qb->sel($object, $field);
        $sql .= $this->qb->where($this->qb->equal('id', "tb.{$fieldData['nombre']}")) . ") as {$fieldData['nombre']}";
        return $sql;
    }
    
    private function setEnumDependIntSubquery(array $fieldData): string
    {
        list($object, $field) = explode('|', $fieldData['externo']);
        $sql  = "( " .$this->qb->sel($object, $field);
        $sql .= $this->qb->where($this->qb->equal('id', "tb.{$fieldData['nombre']}"));
        $sql .= $this->qb->addAnd($this->qb->equal($fieldData['clave'], "tb.{$fieldData['clave']}")) . ") as {$fieldData['nombre']}";
        return $sql;
    }
    
    private function setIdData()
    {
        return array(
            'nombre' => 'id',
            'tipo' => 'INTEGER',
            'traducir' => 0
        );
    }
    
    public function searchTranslation($tableName, $name, $lang)
    {
        $sub  = $this->qb->sel('translations', '`value`'); 
        $sub .= $this->qb->where($this->qb->equal('id_object', 'g.id'));
        $sub .= $this->qb->addAnd($this->qb->equal('object', ucfirst(strtolower($tableName))));
        $sub .= $this->qb->addAnd($this->qb->equal('type', 'data'));
        $sub .= $this->qb->addAnd($this->qb->equal('name', $name));
        $sub .= $this->qb->addAnd($this->qb->equal('language_code', $lang));
        return $sub;
    }
    
    private function searchTableFields(string $tableName): array
    {
        $sub  = $this->qb->sel('tablas', '`id`');
        $sub .= $this->qb->where($this->qb->equal('nombre', ':tablename'));
        $sql  = $this->qb->sel('columnas', '*');
        $sql .= $this->qb->where($this->qb->equal('id_tabla', "({$sub})"));
        $sql .= $this->qb->addAnd($this->qb->distinct('tipo', 'CHILD')) . ';';
        $result = $this->executeQuery($sql, array('tablename' => $tableName));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] == 0) {
            return [];
        }
        array_unshift($result['Records'], $this->setIdData());
        return $result['Records'];
    }
    
    private function searchTableFieldsOnSchema(string $tableName): array 
    {
        $sql = "SELECT COLUMN_NAME nombre, upper(DATA_TYPE) tipo, 0 'traducir' FROM INFORMATION_SCHEMA.COLUMNS";
        $sql .= $this->qb->where($this->qb->equal('table_schema', DB_NAME));
        $sql .= $this->qb->addAnd($this->qb->equal('table_name', ':tableName'));
        $result = $this->executeQuery($sql, array('tableName' => $tableName));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] == 0) {
            return [];
        }
        return $result['Records'];
    }
    
    private function setFieldsSearch(string $tableName): array 
    {
        $result = $this->searchTableFields($tableName);
        if (count($result) == 0) {
            return $this->searchTableFieldsOnSchema($tableName);
        }    
        return $result;
    }
    
}