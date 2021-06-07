<?php

namespace System\Core;

use System\Traits\UtilitiesTrait as UtilitiesTrait;
use System\Factories\Factory;

abstract class AbstractModel 
{
    use UtilitiesTrait;
    
    public $mapper;
    protected $entity;
    protected $session;
    protected $factory;


    public function __construct(AbstractEntity $entity, AbstractMapper $mapper, $session, $queryBuilder) 
    {
        $this->factory = new \System\Factories\Factory();
        $this->entity = $entity;
        $this->mapper = $mapper;
        $this->session = $session;
        $this->qb = $queryBuilder;
    }
    
    public function getWebContent(string $className, string $lang)
    {
        $object   = strtolower($this->clean($className));
        $fileName = "lang_" . $lang . ".json";
        $language = FileManager::readJsonFile(LANG_PATH, $fileName);
        $objectContent = isset($language[$object]) ? $language[$object] : [];
        $commonContent = FileManager::readJsonFile(LANG_PATH, $fileName)['common'];
        if (!is_array($commonContent)) {
            return $objectContent;
        }
        foreach($commonContent as $key => $value) {
            $objectContent[$key] = $value;
        }
        return $objectContent;
    }
    
    private function clean(string $value): string 
    {
        if (strpos($value, 'Base') !== false) {
            return str_replace(array("App\\Controllers\\Base\\", "BaseController"), '', $value);
        }
        return str_replace(array("App\\Controllers\\", "Controller"), '', $value);
    }
    
    public function createObjectData(array $params)
    { 
        if (empty($params['objectName'])) {
            return array();
        }
        
        $objName = $params['objectName'];
        $fatherName = !empty($params['fatherName']) ? $params['fatherName'] : '';
        $returnType = $params['returnType'];
        
        $this->factory->setConfig('model', 'column', '', 'codegen', '', $this->session);
        $adminTableHeaders = $this->factory->create()->searchColNameAndLabel($objName);
        
        $method = "search" . $this->toCamelCase($objName, true);
        $filter = $this->setFilterData($params, $adminTableHeaders['Records']);
        
        $this->factory->setConfig('model', $objName, '', 'model', '',$this->session);
        $adminTable = $this->factory->create()->mapper->$method($filter);
        
        $objectData = array(
            'objName' => $objName,
            'fatherName' => !empty($fatherName) ? $fatherName : $objName,
            'withTable' => false,
            'tableHeaders' => $adminTableHeaders,
            'tableData' => $adminTable
        );
        
        if ($returnType === 'json') {
            echo json_encode($objectData);
            return;
        }
        return $objectData;
    }
    
    private function setModelData($object, $session, $subType)
    {
        $data = new \stdClass();
        $data->factoryType = 'model';
        $data->object = $object;
        $data->action = '';
        $data->session = $session;
        $data->subType = $subType;
        return $data;
    }
    
    private function setFilterData($filterParams, $columnsData)
    {
        if (empty($filterParams['dataId']) || empty($filterParams['fatherName'])) {
            return array();
        }
        if (!$this->searchObjectDataType($columnsData)) {
            return array('id_'. $filterParams['fatherName'] => $filterParams['dataId']);
        }  
        return array(
            'object' => $filterParams['fatherName'],    
            'id_object' => $filterParams['dataId']
        ); 
    }
    
    private function searchObjectDataType($data) 
    {
        $object   = false;
        $idObject = false;
        foreach ($data as $search) {
            if ($search['nombre'] === 'object' ) {
                $object = true;
            }
            if ($search['nombre'] === 'id_object' ) {
                $idObject = true;
            }
            if ($object && $idObject) {
                break;
            }
        }
        return ($object && $idObject);
    }
    
    public function createJsonForm($params)
    {   
        $this->debug($params, 'abstractmodel createjsonform');
        
        $data = !empty($params['formData']) ? $params['formData'] : [];
        
        $realForm = new \System\Core\FormFactory(
            $params['actionType'], 
            $params['formName'], 
            $this->session, 
            'post', 
            '', 
            10, 
            $params['formFather'], 
            $params['parentId'],
            $data
        );
        
        echo json_encode($realForm->drawJsonForm());
    }
    
    public function getAdminTableSpecialFields($params)
    {
        echo Controls::drawCompleteTable(
            $params['objectName'], 
            $params['fatherName'], 
            false, 
            array(), 
            array()
        );
    }
}
