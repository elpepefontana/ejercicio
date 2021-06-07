<?php

namespace System\Core;

use System\Storage\DataBase as Database;
use System\Core\Session as Session;
use System\Core\QueryBuilder as QueryBuilder;
use \System\Storage\StorageInterface as StorageInterface;

abstract class AbstractMapper 
{

    use \System\Traits\UtilitiesTrait;
    
    public $storage;
    public $qb;
    protected $limit;
    protected $tableName;
    protected $session;
    
    public function __construct(StorageInterface $storage, Session $session, QueryBuilder $queryBuilder) 
    {
        $this->storage = $storage;
        $this->qb = $queryBuilder;
        $this->session = $session;
    }

    public function setLimit($value) 
    {
        $this->limit = $value;
    }
    
    public function setTableName($value) 
    {
        $this->tableName = $value;
    } 

    public function getLimit()
    {
        return $this->limit;
    }
    
    public function getTableName()
    {
        return !empty($this->tableName) ? $this->tableName : '';
    }
    
    public function search(string $tableName, $fields, array $data, array $order, $limit)
    {
        $total  = $this->storage->select($tableName, $fields, $data, $order);
        if (empty($limit)) { 
            return $total; 
        }
        $parcial = $this->storage->select($tableName, $fields, $data, $order, $limit);
        
        return array(
            'Result' => $parcial['Result'],
            'Records' => $parcial['Records'],
            'TotalRecordCount' => $total['TotalRecordCount']
        );
    }

    public function create(string $tableName, array $params, array $fields, array $order)
    {
        $exists = $this->storage->select($tableName, $fields, $this->getUniqueArray($params), $order);
        if ($exists['Result'] !== 'OK' || $exists['TotalRecordCount'] > 0) { 
            return $this->setErrorData('Ya existen datos con esas características.');
        }
        $result = $this->storage->insert($tableName, $params);
        if ($result['Result'] !== 'OK') {
            return $this->setErrorData('Error al ingresar los datos.');
        }    
        return $this->storage->select($tableName, $fields, array('id' => $result['Id']), $order);
    }
    
    public function change(string $tableName, array $params)
    {
        return $this->storage->update($tableName, $params);
    }
    
    public function erase(string $tableName, array $params)
    {
        return $this->storage->delete($tableName, array('id' => $params['id']));
    }
    
    public function searchById($params)
    {
        return $this->select(
            $this->getTableName(), 
            array(), 
            array('id' => $params['id']), 
            array('id')
        );
    }

    public function comboSearch(string $tableName, array $data = [], array $params, string $search = 'id')
    {
        return $this->storage->selectOptions($tableName, $data, $params['extfield_name'], $search, true, true);
    }
    
    public function prepareAndUploadImages(string $originalFile, string $destenyFile): bool 
    {
        if (!FileManager::saveUploadedFile($originalFile, $destenyFile)) {
            return false;
        }
        if (IMAGES_RESIZE) {
            FileManager::imageResize($destenyFile);
        }
        return true;
    }
    
    public function setErrorArray(string $text): array
    {
        return array('Result' => 'ERROR', 'Message' => $text);
    }
    
    public function deleteImages(int $rowId, string $path): bool 
    {
        $aRow = $this->searchById($rowId);
        if (!is_array($aRow)) {
            return false;
        }
        $fileNameAndPath = $path . $aRow['Records'][0]['thumb_name'];
        return FileManager::deleteFile($fileNameAndPath);
    }

    public function genFolderCombo(string $dir, string $option = ''): array 
    {
        $list = FileManager::listFilesInDir(ROOT. $dir);
        if (!is_array($list)) { 
            return $this->setErrorData('El Directorio no existe.'); 
        }
        if (!empty($option)) {
            $options[] = array('DisplayText' => ucfirst($option), 'Value' => $option);
        }
        foreach ($list as $file) {
            if ($file === '.DS_Store') {
                continue;
            }
            $val = str_replace(array('Controller.php', 'Model.php', 'View.php'), '', $file);
            $options[] = array('DisplayText' => $val, 'Value' => $val);
        }
        array_unshift(
            $options, 
            array('DisplayText' => '(Ninguno)', 'Value' => 'NULL')
        );
        return array('Result' => 'OK', 'Options' => $options);
    }
    
}
