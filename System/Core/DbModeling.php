<?php

namespace System\Core;

class DbModeling extends \System\Storage\Database
{

    public $message;
    
    public function __construct(Session $session, QueryBuilder $queryBuilder)
    {
        parent::__construct($session, $queryBuilder);
        $this->message = "";
    }
    
    public function setMessage($message)
    {
        $this->message = $message;
    }
    
    public function getMessage()
    {
        return $this->message;
    }
    
    //****************************************** Funciones de creación de base de datos **************************/

    public function createTable($table)
    { 
        $method = 'genCreateTable' . DB_TYPE;
        $result = $this->$method($table);
        if ($result['Result'] === 'OK') {
            $this->message = "\tTabla \"" . ucfirst($table) . "\" creada.\n";
            return true;   
        } else {
            $this->message = "\tError creando tabla \"" . ucfirst($table) . "\": " . $result['Message'] . "\n";
            return false;
        }      
    }
    
    public function genCreateTableMariaDB($table)
    {
        $tableName = $this->stripChars($table);
        $sql = "CREATE TABLE  {$tableName}
               (id INT(20) NOT NULL auto_increment PRIMARY KEY);"; 
        return $this->executeQuery($sql);     
    }
    
    public function genCreateTableMySql($table)
    {
        $tableName = strtolower($this->stripChars($table));
        $sql = "CREATE TABLE IF NOT EXISTS " . $tableName . "
               (id INT(20) NOT NULL auto_increment PRIMARY KEY);"; 
        return $this->executeQuery($sql);      
    }
    
    public function addColumns(string $tableId, string $table)
    {
        $result = $this->searchColumnsDataByTableId($tableId);
        if ($result['Result'] !== 'OK') {
            return false;
        }
        $tableName = $this->stripChars($table);
        $this->iterateAndCreateTableColumns($result, $tableName);
    }
    
    private function iterateAndCreateTableColumns($data, $tableName)
    {
        $res = false;
        $method = 'genAddColumnQuery' . DB_TYPE;
        foreach ($data['Records'] as $row) {
            $nombre = $this->stripChars($row['nombre']);
            $sql    = $this->$method($tableName, $nombre, $row['tipo'], $row['longitud'], $row['nulo']);
            $addCol = $this->executeQuery($sql);
            if ($addCol['Result'] !== 'OK') {
                $this->message .= "\t\tSQL: Columna \"" . ucfirst($row['nombre']) . "\" NO agregada\n\tError: " . $addCol['Message'] . "\n";
                $res = false;
                continue;
            }
            $this->message .= "\t\tColumna \"" . ucfirst($row['nombre']) . "\" Ok.\n";
            $res = true;
        }
        return $res;   
    }
    
    public function stripChars($str)
    {
        $search  = array(' ', 'á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ');
        $replace = array(' ', 'a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U', 'n', 'N');
        return str_replace($search, $replace, $str);
    }
    
    public function genOneColumn($table, $column, $type, $size, $null)
    {
        $tableName = $this->stripChars($table);
        $colName   = $this->stripChars($column);
        $sql    = $this->genAddColumnQueryMariaDB($tableName, $colName, $type, $size, $null);
        $result = $this->executeQuery($sql);
        if ($result['Result'] !== 'OK' || empty($result['Records'])) { 
            return false;
        }
        return true;
    }
    
    public function getColumnName($id)
    {
        $sql = "SELECT * FROM columnas WHERE id = :id ORDER BY orden ASC;";
        $result = $this->executeQuery($sql, array('id' => $id));
        if ($result['Result'] !== 'OK' || empty($result['Records'])) { 
            return false;
        }
        return strtolower(str_replace(' ', '_', $result['Records'][0]['nombre']));
    }
    
    public function getColumnSearchable($id)
    {
        $sql = "SELECT * FROM columnas WHERE `id` = :id AND `buscable` = 1 ORDER BY `orden` ASC;";
        $result = $this->executeQuery($sql, array('id' => $id));
        if ($result['Result'] !== 'OK' || empty($result['Records'])) { 
            return false;
        }
        return $result['Records'][0]['buscable'] === 1 ? true : false;
    }
    
    public function genAddColumnQueryMysql($tableName, $name, $type, $size, $null)
    {
        $name = $this->stripChars($name);
        if (!$this->searchColumn($tableName, $name)) {
            $sql = "IF NOT EXISTS
                    (
                        (
                            SELECT * FROM information_schema.COLUMNS    
                            WHERE `TABLE_SCHEMA` = DATABASE()
                                AND `COLUMN_NAME` = '{$name}'   
                                AND `TABLE_NAME` = '{$tableName}'
                        ) 
                    )  
                    THEN
                    ALTER TABLE `{$tableName}` ADD COLUMN ";
            $sql .= $this->createColDefinition($name, $type, $size, $null);
            $sql .= " END IF;";
            return $sql;
        }
        $sql = $this->changeColNameAndDefinition(
            $tableName, 
            $this->createColDefinition($name, $type, $size, $null, true)
        );
        return $sql;
    }
        
    public function genAddColumnQueryMariaDB($table, $column, $type, $size, $null)
    {
        $tableName = strtolower($this->stripChars($table));
        $name = strtolower($this->stripChars($column));
        if (!$this->searchColumn($tableName, $name)) {
            $sql  = "ALTER TABLE `{$tableName}` ADD COLUMN ";            
            $sql .= $this->createColDefinition($name, $type, $size, $null);
        } else {
            $sql = $this->changeColNameAndDefinition(
                $tableName, 
                $this->createColDefinition($name, $type, $size, $null, true)
            );
        }
        return $sql;
    }
    
    public function createColDefinition($column, $type, $size, $null, $modify = false) 
    {
        $name = ($modify) ? " IF EXISTS `" . strtolower($this->stripChars($column)) . "`" : "`" . strtolower($this->stripChars($column)) . "`";
        $sql  = "";
        switch($type){
            case 'BOOLEAN':
                $sql .= " {$name} TINYINT({$size}) "; 
                break;
            case 'DECIMAL':
            case 'FLOAT':
                $type = ($type === 'DECIMAL' || $type === 'FLOAT' || $type === 'MONEY') ? 'DECIMAL' : $type;
                $sql .= " {$name} {$type} ({$size}) ";                        
                break;
            case 'MONEY':
                $size = $this->setMoneyDecimalPart($size);
                $type = 'DECIMAL';
                $sql .= " {$name} {$type} ({$size}) ";                        
                break;
            case 'ENUMINT':
            case 'ENUMDEPENDINT':
            case 'INTEGER':
            case 'LOOKUPINT':
                $type = ($type === 'LOOKUPINT' or $type === 'ENUMINT' or $type === 'ENUMDEPENDINT') ? 'INTEGER' : $type;
                $sql .= " {$name} {$type} ({$size}) ";                        
                break;
            case 'DATE':
            case 'DATETIME':
                $sql .= " {$name} {$type}";                        
                break;
            case 'ENUM':
            case 'ENUMFOLDER':
            case 'IMAGE':
            case 'LOOKUPVAR':
            case 'OBJECT':
            case 'PASSWORD':
            case 'VARCHAR':
                $sql .= " {$name} VARCHAR({$size}) ";                        
                break; 
            case 'TEXT(250)':
                $size = 250;
                $sql .= " {$name} VARCHAR({$size}) ";                        
                break;
            case 'TEXT(500)':
                $size = 500;
                $sql .= " {$name} VARCHAR({$size}) ";                        
                break;
            case 'TEXT(750)':
                $size = 750;
                $sql .= " {$name} VARCHAR({$size}) ";                        
                break;
            case 'TEXT(1000)':
                $size = 1000;
                $sql .= " {$name} VARCHAR({$size}) ";                        
                break;
            case 'TEXT(1500)':
                $size = 1500;
                $sql .= " {$name} VARCHAR({$size}) ";                        
                break;
            case 'TEXT(2000)':
                $size = 2000; 
                $sql .= " `{$name}` VARCHAR({$size}) ";                        
                break;
            case 'TEXT(2500)':
                $size = 2500;
                $sql .= " {$name} VARCHAR({$size}) ";                        
                break;
            case 'EMAIL':
                $size = 400;
                $sql .= " {$name} VARCHAR({$size}) ";                        
                break;                  
        }
        if ($null == 1) {
            $sql .= " NULL;";
        } else {
            $sql .= " NOT NULL;";
        }
        return $sql;
    }
    
    private function setMoneyDecimalPart($size)
    {
        if (strpos($size, ',') === false && strpos($size, '.') === false) {
            $size = $size .',2';
        }
        return $size;
    }
    
    public function dropColumn($tableName, $column)
    {
        $tableName = strtolower(str_replace(' ', '_', $tableName));
        $column    = strtolower(str_replace(' ', '_', $column));
        $sql       = "ALTER TABLE {$tableName} DROP COLUMN {$column};";
        return $this->executeQuery($sql);
    }
    
    public function changeColNameAndDefinition($tableName, $newColNameAndDefinition)
    {
        $tableName = strtolower($this->stripChars($tableName));
        $sql       = "ALTER IGNORE TABLE {$tableName} MODIFY {$newColNameAndDefinition}";
        return $sql;
    }
    
    public function changeColName($table, $col, $newCol)
    {
        $tableName = $this->stripChars($table);
        $column    = $this->stripChars($col);
        $newColumn = $this->stripChars($newCol);
        $sql = "ALTER TABLE {$tableName} RENAME COLUMN {$column} TO {$newColumn}";
        return $this->executeQuery($sql);
    }

    public function changeTableName($tableName, $newTableName)
    {
        $tableName = $this->stripChars($tableName);
        $newTableName = strtolower($this->stripChars($newTableName));
        $sql = "ALTER TABLE {$tableName} RENAME {$newTableName}";
        $result =  $this->executeQuery($sql);
        return ($result['Result'] === 'OK') ? true : false;
    }
    
    //****************************************** CREACION Y MODIFICACION DE INDICES **************************/

    public function createTableIndex($tableName, $indexName, $indexColDefinition) 
    {
        $tableName = strtolower(str_replace(' ', '_', $tableName));
        $sql = "ALTER TABLE {$tableName} ADD INDEX {$indexName} ({$indexColDefinition})";
        $result =  $this->executeQuery($sql);
        return ($result['Result'] === 'OK') ? true : false;       
    }

    public function setUniqueFieldList($tableId, $separator)
    {
        $result  = false;
        $aUnique = $this->searchUniqueColumns($tableId);
        $uniqueFields = is_array($aUnique) ? implode($separator, $aUnique) : false;
        return !empty($uniqueFields) ? $uniqueFields : false;
    }
    
    public function createUniqueIndex($tableName, $indexName, $colList) 
    {// indexcoldefinition puede ser un string de columnas separadasp por coma 
        $sql = "ALTER TABLE IGNORE {$tableName} ADD UNIQUE {$indexName} ({$colList})";
        $result =  $this->executeQuery($sql);
        return ($result['Result'] === 'OK') ? true : false;       
    }

    public function setUniqueFieldsSearch($aUnique, $aRowData)
    {
        if (!is_array($aUnique) || !is_array($aRowData)) {
            return '';
        }
        $out       = '';
        $aToSearch = array_keys($aRowData);
        foreach ($aUnique as $unique) {
            $index = $this->setUniqueId($unique, $aToSearch);
            if (empty($index) || strtotime($aRowData[$index]) !== false) {
                continue;
            }
            $value = trim($aRowData[$index]);
            if (empty($value)) {
                continue;
            }
            $value = is_numeric($value) ? $value : "'{$value}'"; 
            $out  .= empty($out) ? "{$unique} = " . $value : " AND {$unique} = " . $value;
        }
        return $out;
    }
    
    private function setUniqueId($unique, $keys)
    {
        if(strtolower($unique) !== 'id_externo') { 
            return $unique; 
        }
        $options = array('ID','Id','id','iD');
        foreach ($keys as $key) {
            foreach ($options as $option) {
                if ($key === $option) { 
                    return $option;
                }
            }
        }
    }
    
    public function validateAndCreateUnique($tableName, $tableId)
    {
        $uniqueFields = $this->setUniqueFieldList($tableId,', ');
        $uniqueName   = "INDEX_" . strtoupper($tableName) . "_UNIQUE"; 
        if (empty($uniqueName) || empty($uniqueFields)) { 
            return false; 
        }
        $searchIndex = $this->searchTableIndex($tableName, $uniqueName);
        $res    = $searchIndex === true ? $this->dropTableIndex($tableName, $uniqueName) : true;
        $result = $res         === true ? $this->createUniqueIndex($tableName, $uniqueName, $uniqueFields) : false;
        return $result;
    }
    
    public function dropTableIndex($table, $indexName)
    {
        $tableName = strtolower(str_replace(' ', '_', $table));
        $sql = "ALTER TABLE {$tableName} DROP INDEX {$indexName}";
        $result =  $this->executeQuery($sql);
        return ($result['Result'] === 'OK') ? true : false;
    }
    
    public function dropTable($table)
    {
        $tableName = strtolower(str_replace(' ', '_', $table));
        $sql = "DROP TABLE IF EXISTS {$tableName};";
        $result = $this->executeQuery($sql);
        return ($result['Result'] === 'OK') ? true : false;
    }
    
    public function changeIndexName($table, $indexName, $newIndexName)
    {
        $table = strtolower(str_replace(' ', '_', $table));
        $sql = "ALTER TABLE {$table} RENAME INDEX {$indexName} TO {$newIndexName}";
        $result =  $this->executeQuery($sql);
        return ($result['Result'] === 'OK') ? true : false;
    }
    
    public function addForeignKeyToExistingColumn($table, $column, $fkTable, $fkColumns)
    {
        $table = $this->stripChars($table);
        $column = $this->stripChars($column);
        $sql = "ALTER TABLE {$table} ADD FOREIGN KEY ({$column}) REFERENCES {$fkTable} ({$fkColumns})";
        $result =  $this->executeQuery($sql);
        return ($result['Result'] === 'OK') ? true : false;
    }
    
    // INDEX SEARCH FUNCTIONS - FUNCIONES DE BUSQUEDA DE INDICES
    
    public function searchColumn($tableName, $column)
    {
        $table = strtolower(str_replace(' ', '_', $tableName));
        $col   = strtolower(str_replace(' ', '_', $column));
        $sql = "SELECT * 
                FROM information_schema.COLUMNS
                WHERE TABLE_SCHEMA = DATABASE()
                    AND TABLE_NAME = :table 
                    AND COLUMN_NAME = :column";
        $result = $this->executeQuery($sql, array('table' => $table, 'column' => $col));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] === 0) {
            return false;
        }
        return true;
    }
    
    public function searchTableIndex($tableName, $indexName)
    {
        $sql = "SELECT * FROM INFORMATION_SCHEMA.STATISTICS WHERE table_name = :tableName AND index_name = :indexName ;";
        $result = $this->executeQuery($sql, array('tableName' => $tableName, 'indexName' => $indexName));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] === 0) {
            return false;
        }
        return true;
    }
    
    // INNER SEARCH FUNCTIONS - FUNCIONES DE BUSQUEDA INTERNA
    
    public function isUnique($tableId, $colName)
    {
        $sql = "SELECT * FROM columnas WHERE id_tabla = :tableId AND nombre = :colName AND unico = 1;";
        $result = $this->executeQuery($sql, array('tableId' => $tableId, 'colName' => $colName));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] === 0) {
            return false;
        }
        return true;
    }
    
    public function searchForDataDuplicates($tableName, $fieldQuery)
    {
        $sql = "SELECT id FROM {$tableName} WHERE {$fieldQuery};";
        $result = $this->executeQuery($sql);
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] === 0) {
            return false;
        }
        return true;
    }
    
    private function searchColumnsDataByTableId($tableId)
    {
        $sql = "SELECT * FROM `columnas` WHERE `id_tabla` = :tableId AND `tipo` <> 'CHILD' ORDER BY `orden` ASC;";
        return $this->executeQuery($sql, array('tableId' => $tableId));
    }
    
    public function searchUniqueColumns($tableId) 
    {
        $sql = "SELECT nombre FROM columnas WHERE id_tabla = :tableId AND unico = 1;";
        $result = $this->executeQuery($sql, array('tableId' => $tableId));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] === 0) {
            return false;
        }
        return $result['Records'];
    }
    
    public function getTableName($tableId)
    {
        $sql = "SELECT nombre FROM tablas WHERE id = :tableId";
        $result = $this->executeQuery($sql, array('tableId' => $tableId));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] === 0) {
            return false;
        }
        return strtolower(str_replace(' ', '_', $result['Records'][0]['nombre']));
    }
    
    public function getTableId($name)
    {
        $sql = "SELECT id FROM tablas WHERE nombre = :name";
        $result = $this->executeQuery($sql, array('nombre' => $name));
        if ($result['Result'] !== 'OK' || $result['TotalRecordCount'] === 0) {
            return false;
        }
        return $result['Records'][0]['id'];
    }
}