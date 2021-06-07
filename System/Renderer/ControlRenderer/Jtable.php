<?php

namespace System\Renderer\ControlRenderer;

use System\Core\Session as Session;
use System\Renderer\ControlRenderer\ControlInterface;

class Jtable extends AbstractControl implements ControlInterface
{
    use \System\Traits\UtilitiesTrait;
    
    private $table;
    private $column;
    private $template;
    private $factory;
    
    public function __construct($typeData, $data)
    {
        parent::__construct($typeData, $data);
        $this->setData();
        $session = new \Session();
        $this->factory = new \System\Factories\Factory();
        
        $this->table = $this->factory->create('model', 'table', '', 'codegen', '', $session);
        $this->column = $this->factory->create('model', 'column', '', 'codegen', '', $session);
        $this->template = $this->factory->create('model', 'template', '', 'codegen', '', $session);
        
    }
    
    private function setData()
    {
        foreach ($this->typeData as $key => $value) {
            if (!property_exists(__CLASS__, $key)) {
                continue;
            }
            $this->$key = $value;
        }
    }
    
    public function render()
    {
        is_array($this->data) ? extract($this->data) : '';
        $tableId  = $id;
        $content  = "";
        $content .= $this->iterateSearchableCols($id_tabla);
        $content .= "                <div id=\"" . ucfirst($nombre) . "\" ></div>\n";
        $content .= "                " . Controls::drawModal('translate', 'Traducir:', 'bg-darkBlue fg-white', '', '' , '');
        $content .= "                    <script type=\"text/javascript\">\n";
        $content .= "                        var homePath = '<?=HOME;?>';\n";
        $content .= $this->column->searchImageFieldsByTableId($tableId)['Result'] === 'OK' ? "                            galleryPath = '<?=" . strtoupper($nombre) . "_PATH;?>';\n\n" : "";
        $content .= $this->genJavaHeadFunctions();
        $content .= $this->genJtableHeadDefinition($nombre, $titulo, $insertar, $paginacion_general, $ordenamiento_general, $inicio_indice, $tamano_pagina);
        $content .= $this->genJtableActions($nombre, false, $id_tabla);
        $content .= $this->genJtableFields($id_tabla, $nombre, $titulo, 4, '');
        $content .= "                            formCreated: function (event, data) {\n";
        $content .= "                                data.form.validationEngine();\n";
        $content .= "                                $(data.form).addClass(\"custom_horizontal_form_field\");\n";
        $content .= "                            },\n";
        $content .= "                            formSubmitting: function (event, data) {\n";
        $content .= "                                return data.form.validationEngine('validate');\n";
        $content .= "                            },\n";
        $content .= "                            formClosed: function (event, data) {\n";
        $content .= "                                data.form.validationEngine('hide');\n";
        $content .= "                                data.form.validationEngine('detach');\n";
        $content .= "                            }\n";
        $content .= "                        });\n";
        $content .= "                        $('#" . ucfirst($nombre) . "').jtable('load');\n\n";
        $content .= $this->genJavaFootFunctions($nombre);
        $content .= "                   });\n\n";
        $content .= $this->genMultipleImagesDecide($nombre);
        //$content .= $this->genSearchTranslationFields();
        //$content .= $this->genCreateSpecificTranslation();
        $content .= "                    </script>\n";
        $sectionName = $this->template->searchTemplateNameById($id_template);
        $section  = Controls::drawDiv($sectionName, '', $content, '');
        return $section;
    }
    
    public function childContent($nombre, $etiqueta, $table, $titulo, $icono, $fk)
    {
        $tableId  = $this->table->searchTableIdByTableName($nombre);   
        $tableId === false ? exit("Tabla {$nombre} no encontrada") : ''; 
        $content  = $this->genJtableChildHeadDefinition($nombre, $etiqueta, $table, $icono);
        $content .= $this->genJtableChildActions($nombre, $table, false, $fk, $tableId);
        $content .= $this->genJtableFields($tableId, $table, $titulo, 6, $nombre);
        $content .= $this->genJtableChildEnd();
        return $content;
    }
  
    /**************************************** FUNCIONES DE CONTENIDO GENERALES *************************************/
    
    
    // JAVA HEADERS FUNCTIONS - FUNCIONES DE CABECERA DE JAVA
    
    private function genJavaHeadFunctions()
    {
        $content  = "                    var files;\n\n";
        $content .= "                    function getVars(url){\n";
        $content .= "                        var formData = new FormData();\n";
        $content .= "                        var split;\n";
        $content .= "                        \$.each(url.split(\"&\"), function(key, value) {\n";
        $content .= "                            split = value.split(\"=\");\n";
        $content .= "                            formData.append(split[0], decodeURIComponent(split[1].replace(/\+/g, \" \")));\n";
        $content .= "                        });\n";    
        $content .= "                        return formData;\n";
        $content .= "                    }\n\n";    
        $content .= "                    \$( document ).delegate('#img','change', prepareUpload);\n\n";
        $content .= "                    function prepareUpload(event){\n";
        $content .= "                        files = event.target.files;\n";
        $content .= "                    }\n\n";
        return $content;
    }
    
    // JAVA FOOTER FUNCTIONS - FUNCIONES DE PIE DE JAVA
    
    private function genJavaFootFunctions($name)  
    {
        $content  = "                        \$('#search').click(function (e) {\n";
        $content .= "                            e.preventDefault();\n";
        $content .= "                            send = {};\n";
        $content .= "                            send[$('#field').val()] = $('#value').val();\n";
        $content .= "                            $('#" . ucfirst($name) . "').jtable('load', send);\n";
        $content .= "                        });\n\n";
        $content .= "                        \$('#clean').click(function (e) {\n";
        $content .= "                            e.preventDefault();\n";
        $content .= "                            \$('#value').val('');\n";
        $content .= "                            \$('#" . ucfirst($name) . "').jtable('load',{});\n";
        $content .= "                       });\n\n";
        $content .= "                       $('#search').click();\n\n";
        return $content;
    }
    
    private function genJtableHeadDefinition(
        $nombre,
        $titulo, 
        $insertar, 
        $paginacion_general, 
        $ordenamiento_general,
        $inicio_indice,
        $tamano_pagina
    ) {
        $inicio_indice = empty($inicio_indice) ? 0 : $inicio_indice;
        $content  = "                    $(document).ready(function () {\n";
        $content .= "                        $('#" . ucfirst($nombre) . "').jtable({\n";
        $content .= "                            title: '" .ucfirst($titulo) ."',\n";
        $content .= "                            create: " . $this->convertToBolean($insertar) . ",\n";
        $content .= "                            paging: " . $this->convertToBolean($paginacion_general) . ",\n";
        $content .= "                            pageSize: {$tamano_pagina},\n"; 
        $content .= "                            sorting: false,\n";     
        $content .= "                            jtStartIndex: {$inicio_indice},\n";     
        $content .= "                            jtPageSize: {$tamano_pagina},\n"; 
        $content .= "                            jtSorting: 'id ASC',\n";
        $content .= "                            defaultSorting: '{$ordenamiento_general}',\n";
        return $content;
    }
    
    private function genJtableChildHeadDefinition($nombre, $etiqueta, $padre, $icono)
    {
        $content  = "                            {$nombre}: {\n";
        $content .= "                                title: '{$etiqueta}',\n";
        $content .= "                                width: '1%',\n";
        $content .= "                                sorting: true,\n";
        $content .= "                                edit: false,\n";
        $content .= "                                create: false,\n";
        $content .= "                                paging: false,\n";
        $content .= "                                display: function (data) {\n";                         
        $content .= "                                        var \$img = $('<span class=\"mif-{$icono} mif-2x\"></span>');\n";                        
        $content .= "                                        \$img.click(function () {\n";
        $content .= "                                        parentTable = $(\"#". ucfirst($padre) . "\");\n";
        $content .= "                                            var tr = $(this).parents(\"tr\");\n";
        $content .= "                                            isChildRowOpen = parentTable.jtable(\"isChildRowOpen\", tr );\n";                
        $content .= "                                            if(isChildRowOpen){\n";
        $content .= "                                                $(parentTable.jtable(\"getChildRow\", tr)).slideUp();\n";
        $content .= "                                                return;\n";
        $content .= "                                            };\n";
        $content .= "                                            $('#". ucfirst($padre) . "').jtable('openChildTable',\n";
        $content .= "                                                \$img.closest('tr'),\n";
        $content .= "                                                {\n";
        $content .= "                                                    title: data.record.name + ' - {$etiqueta}',\n";
        return $content;
    }
       
    private function convertToBolean($v)
    {
        $out =  ($v = 1) ? 'true' : 'false';
        return $out;
    }
    
    private function genJtableActions($nombre, $active, $tableId)
    {
        $select = $active === true ? 'selectActive' : 'select';
        $delete = $active === true ? 'logicalDelete' : 'delete';
        $select = $this->column->modifyAction($tableId, $select);
        $content  = "                            actions: {\n";
        $content .= "                                listAction: function (postData, jtParams) {\n";
        $content .= "                                    return $.Deferred(function (\$dfd) {\n";
        $content .= "                                        $.ajax({\n";
        $content .= "                                            url: '<?=HOME?>/" . $this->model->toCamelCase($nombre, true) . "/search" . $this->model->toCamelCase($nombre, true) . "/limit=' + jtParams.jtStartIndex + ',' + jtParams.jtPageSize,\n";
        $content .= "                                                type: 'POST',\n";
        $content .= "                                                dataType: 'json',\n";
        $content .= "                                                data: postData,\n";
        $content .= "                                                success: function (data) {\n";
        $content .= "                                                    \$dfd.resolve(data);\n";
        $content .= "                                                },\n";
        $content .= "                                                error: function () {\n";
        $content .= "                                                    \$dfd.reject();\n";
        $content .= "                                                }\n";
        $content .= "                                            });\n";
        $content .= "                                        });\n";
        $content .= "                                    }";
        $content .= "<?php if(\$view->create == 1 or \$view->update == 1 or \$view->delete == 1 ) : ?>\n";
        $content .= ",\n";
        $content .= "<?php else : ?>\n";
        $content .= "\n";
        $content .= "<?php endif; ?>\n";
        $content .= $this->genCreateAction($nombre, $tableId, false, '');
        $content .= $this->genUpdateAction($nombre, $tableId, false, '');
        $content .= $this->genDeleteAction($nombre, $tableId, false, $delete, '');
        $content .= "                           },\n";
        return $content;
    }
    
    private function genCreateAction($tableName, $tableId, $isChild, $clave) 
    {
        $content = '';
        if ($isChild !== false) {
            $object = ucfirst($tableName);
            $send   = !empty($clave) ? "{$clave}=' + data.record.id,\n" : "id_{$isChild}=' + data.record.id,\n";
        } else {
            $object = ucfirst($tableName);
            $send   = "',\n";
        }
        $method = ucfirst($tableName);
        $aImageFields = $this->column->searchImageFieldsByTableId($tableId);
        if (empty($aImageFields)) {
            $content .= "<?php if(\$view->create == 1) : ?>\n";
            $content .= "                                createAction: '<?=HOME?>/{$this->model->toCamelCase($object, true)}/create{$this->model->toCamelCase($method, true)}/{$send}";
            $content .= "<?php endif; ?>\n";
        } else {
            $content .= "<?php if(\$view->create == 1) : ?>\n";
            $content .= "                                createAction: function (postData){\n";
            $content .= "                                    var formData = getVars(postData);\n";
            foreach ($aImageFields as $image) {
                $content .= "                                    if(\$('#{$image['nombre']}').val() !== ''){\n";
                $content .= "                                        formData.append('{$image['nombre']}_upl', \$('#{$image['nombre']}_upl').get(0).files[0]);\n";
                $content .= "                                    }\n";
            }
            $content .= "                                    return $.Deferred(function (\$dfd){\n";
            $content .= "                                        $.ajax({\n";
            $content .= "                                            url: '<?=HOME?>/{$this->model->toCamelCase($object, true)}/create{$this->model->toCamelCase($method, true)}/{$send}";
            $content .= "                                            type: 'POST',\n";
            $content .= "                                            dataType: 'json',\n";
            $content .= "                                            data: formData,\n";
            $content .= "                                            processData: false, // Don't process the files\n";
            $content .= "                                            contentType: false, // Set content type to false as jQuery will tell the server its a query string request\n";
            $content .= "                                            success: function (data){\n";
            $content .= "                                                \$dfd.resolve(data);\n";
            $content .= "                                                \$('#{$object}').jtable('load');\n";
            $content .= "                                            },\n";
            $content .= "                                            error: function(){\n";
            $content .= "                                                \$dfd.reject();\n";
            $content .= "                                            }\n";
            $content .= "                                       });\n";
            $content .= "                                    });\n";
            $content .= "                                },\n";
            $content .= "<?php endif; ?>\n";
        }
        return $content;
    }
    
    private function genUpdateAction($tableName, $tableId, $isChild, $clave)
    {
        $content = '';
        if ($isChild !== false) {
            $object = ucfirst($tableName);
            $send   = !empty($clave) ? "{$clave}=' + data.record.id,\n" : "id_{$isChild}=' + data.record.id,\n";
        } else {
            $object = ucfirst($tableName);
            $send   = "',\n";
        }  
        $method = ucfirst($tableName);
        $aImageFields = $this->column->searchImageFieldsByTableId($tableId);
        if (empty($aImageFields)) {
            $content .= "<?php if(\$view->update == 1) : ?>\n";
            $content .= "                                updateAction: '<?=HOME?>/{$this->model->toCamelCase($object, true)}/change{$this->model->toCamelCase($method, true)}/{$send}";
            $content .= "<?php endif; ?>\n";
        } else {
            $content .= "<?php if(\$view->update == 1) : ?>\n";
            $content .= "                                updateAction: function (postData){\n";
            $content .= "                                    var formData = getVars(postData);\n";
            foreach ($aImageFields as $image) {
                $content .= "                                   if(\$('#{$image['nombre']}').val() !== ''){\n";
                $content .= "                                       formData.append('{$image['nombre']}_upl', \$('#{$image['nombre']}_upl').get(0).files[0]);\n";
                $content .= "                                   }\n";
            }
            $content .= "                                return $.Deferred(function (\$dfd){\n";
            $content .= "                                    $.ajax({\n";
            $content .= "                                        url: '<?=HOME?>/{$this->model->toCamelCase($object, true)}/change{$this->model->toCamelCase($method, true)}/{$send}";
            $content .= "                                        type: 'POST',\n";
            $content .= "                                        dataType: 'json',\n";
            $content .= "                                        data: formData,\n";
            $content .= "                                        processData: false, // Don't process the files\n";
            $content .= "                                        contentType: false, // Set content type to false as jQuery will tell the server its a query string request\n";
            $content .= "                                        success: function (data){\n";
            $content .= "                                            \$dfd.resolve(data);\n";
            $content .= "                                            \$('#{$object}').jtable('load');\n";
            $content .= "                                        },\n";
            $content .= "                                        error: function(){\n";
            $content .= "                                            \$dfd.reject();\n";
            $content .= "                                        }\n";
            $content .= "                                   });\n";
            $content .= "                                });\n";
            $content .= "                            },\n";
            $content .= "<?php endif; ?>\n";
        }
        return $content;
    }
    
    private function genMultipleImagesDecide($tableName)
    {
        $out = '';
        $aChild = $this->column->genChildColumnsDataByTableName($tableName);
        if (!is_array($aChild) || count($aChild) == 0) {
            return $this->genJavaMultipleImagesUpload($tableName);
        }
        foreach($aChild as $child) {
            $out = $this->genJavaMultipleImagesUpload($child['nombre']);
        }
        return $out;
    }
    
    private function genJavaMultipleImagesUpload($tableName) 
    {
        $content = '';
        $aImage   = $this->column->searchImageFieldsByTableName($tableName);
        if (is_array($aImage) && count($aImage) > 0) {
            $image    = $aImage[0]['nombre'];
            $content .= "                        $(\"#frm_massImageUpload\").on('submit',(function(e) {\n";
            $content .= "                            e.preventDefault();\n";
            $content .= "                            var activity = Metro.activity.open({\n";
            $content .= "                                type: 'cycle',\n";
            $content .= "                                style: 'color',\n";
            $content .= "                                overlayClickClose: true\n";
            $content .= "                            });\n";
            $content .= "                            $.ajax({\n";
            $content .= "                                url: '<?=HOME?>/GalleryItem/imageMassiveUpload',\n";
            $content .= "                                type: 'POST',\n";
            $content .= "                                data:  new FormData(this),\n";
            $content .= "                                contentType: false,\n";
            $content .= "                                cache: false,\n";
            $content .= "                                processData:false,\n";
            $content .= "                                success: function(data){\n";
            $content .= "                                    setTimeout(function(){\n";
            $content .= "                                        Metro.activity.close(activity);\n";
            $content .= "                                    }, 1000);\n";
            $content .= "                                    drawInfoBox('Resultado:', 'OK','info','');\n";
            $content .= "                                },\n";
            $content .= "                                error: function(){\n";
            $content .= "                                    setTimeout(function(){\n";
            $content .= "                                        Metro.activity.close(activity);\n";
            $content .= "                                    }, 1000);\n";
            $content .= "                                    drawInfoBox('Error:', 'OK','info','');\n"; 
            $content .= "                                }\n";        
            $content .= "                            });\n";
            $content .= "                        }));\n\n";
            
        }
        return $content;
    }
    
    private function genDeleteAction($tableName, $tableId, $isChild, $delete, $clave)
    {
        if ($isChild !== false) {
            $object = ucfirst($tableName);
            $child  = ucfirst($tableName);
            $send   = !empty($clave) ? "{$clave}=' + data.record.id,\n" : "id_{$isChild}=' + data.record.id,\n";
        } else {
            $object = ucfirst($tableName);
            $child  = ucfirst($tableName);
            $send   = "'\n";
        }
        $aImage   = $this->column->searchImageFieldsByTableName($tableId);
        $content  = "";
        $content .= "<?php if(\$view->delete == 1) : ?>\n";
        if (empty($aImage)) {
            $content .= "                               deleteAction: '<?=HOME?>/{$this->model->toCamelCase($object, true)}/erase{$this->model->toCamelCase($child, true)}/{$send}";
        } else {
            $content .= "                               deleteAction: '<?=HOME?>/{$this->model->toCamelCase($object, true)}/erase{$this->model->toCamelCase($child, true)}/id_{$tableName}=' + data.record.id + '{$send}";
        }
        $content .= "<?php endif; ?>\n";
        return $content;
    }
    
    private function genJtableChildActions($nombre, $table, $active, $clave, $tableId)
    {
        $select = $active === true ? 'selectActive' : 'select';
        $delete = $active === true ? 'logicalDelete' : 'delete';
        $select = $this->column->modifyAction($tableId, $select);
        $foreignKey   = !empty($clave) ? "{$clave}=' + data.record.id" : "'";
        $childTableId = $this->table->searchTableIdByTableName($nombre);
        $content  = "                                                    actions: {\n";
        $content .= "                                                        listAction:   '<?=HOME?>/" . $this->model->toCamelCase($nombre, true) . "/search" . $this->model->toCamelCase($nombre, true) . "/{$foreignKey}\n";
        $content .= "<?php if(\$view->create == 1 or \$view->update == 1 or \$view->delete == 1 ) : ?>\n";
        $content .= ",\n";
        $content .= "<?php else : ?>\n";
        $content .= "\n";
        $content .= "<?php endif; ?>\n";
        $content .= $this->genCreateAction($nombre, $childTableId, $table, $clave);
        $content .= $this->genUpdateAction($nombre, $childTableId, $table, $clave);
        $content .= $this->genDeleteAction($nombre, $childTableId, $table, $delete, $clave);
        $content .= "                                                    },\n";
        return $content;
    }
    
    private function genJtableChildEnd()
    {
        $content  = "                                                }, function (data) {\n";
        $content .= "                                                    data.childTable.jtable('load');\n";
        $content .= "                                                });\n"; 
        $content .= "                                            });\n";
        $content .= "                                        return \$img;\n";
        $content .= "                                    }\n";
        $content .= "                               }\n";
        return $content;
    }
    
    private function genJtableFields($tableId, $table, $titulo, $tabs, $nombre)
    {
        $nTabs = $this->setNtabs($tabs);
        $content  = $nTabs . "fields: {\n"; 
        $content .= $nTabs . "    id: {key: true,list: false},\n";
        $aFields = $this->column->searchNonExcludedFields($tableId);
        $a = 1;
        $rowCount = count($aFields);
        foreach ($aFields as $row) {
            $columnCount = count($row);
            $content .= $this->setJtableContentByType($table, $row, $columnCount, $titulo, $tabs + 1, $nombre);
            $content .= $a < $rowCount ? ",\n" : "\n";
            $a++;
        } 
        $content .= $this->genLanguagesButton($table, $nTabs);
        $content .= $nTabs . "},\n";
        return $content;
    }
    
    private function setNtabs($nTabs) 
    {
        $out = "";
        for ($a = 0; $a < $nTabs; $a++) {
            $out .= "    ";
        }
        return $out;
    }
    
    private function genValidateOptions(int $null, string $validationType){
        $required  = $null == 0  || $null === null ? 'required' : '';
        $validType = !empty($validationType) ? "custom[{$validationType}]" : '';
        if (!empty($required) && !empty($validType)) {
            $valida = "{$required},{$validType}";
        } elseif (!empty($required) && empty($validType)) {
            $valida = "{$required}";
        } elseif (!empty($validType) && empty($required)) {
            $valida = "{$validType}";
        } else {
            $valida = '';
        }
        $validate  = !empty($valida) ? "inputClass: 'validate[{$valida}]'" : '';
        return $validate;
    }
    
    private function setDefaultValue($value, $table, $tableName) 
    {
        if (empty($value)) {
            return '';
        }
        $out = ",defaultValue: '{$value}'";
        $obj = !empty($table) ? $this->model->toCamelCase($table, true) : $this->model->toCamelCase($tableName, true);
        if (strpos($value, '[FATHER]') !== false) {
            $out = ",defaultValue: '{$obj}'";
        }    
        return $out;
    }
    
    private function setJtableContentByType($table, $row, $count, $titulo, $tabs, $tableName) 
    {
        is_array($row) ? extract($row) : null;
        $nTabs    = $this->setNtabs($tabs);
        $colCount = intdiv(100,$count);
        $list     = ($mostrar != 1) ? "list: false," : '';
        $edit     = ($editable != 1) ? "edit: false," : '';
        $crear    = ($crear != 1) ? "create: false," : '';
        
        $icono = $tipo === 'OBJECT' ? '[FATHER]' : $icono;
        
        $default =  $this->setDefaultValue($icono, $table, $tableName);
        $out      = "";
        switch($tipo){
            case 'BUTTON':
                $out  = $nTabs . "{$nombre}: {";
                $out .= $nTabs . "    title: '{$etiqueta}',\n";
                $out .= $nTabs . "    width: '{$colCount}%',\n";
                $out .= $nTabs . "    create: false,\n";
                $out .= $nTabs . "    {$edit}\n";
                $out .= $nTabs . "    display: function(data) {\n";
                $out .= $nTabs . "        dataToSend = \"{id : \" + data.record.id + \", name: \'\" + data.record.nombre + \"\'}\";\n";
                $out .= $nTabs . "        return '<button type=\"button\" class=\"button success outline small\" onclick=\"sendAjaxReq(\'/Report/getFieldCount/\', \'post\', \'text\',' + dataToSend + ', \'' + data.record.id + '\',\'' + data.record.nombre + '\');\">{$etiqueta}</button>';";
                $out .= $nTabs . "    }";
                $out .= $nTabs . "}";
                break;
            case 'BOOLEAN':
                $out = $nTabs . "{$nombre}:{ {$list} {$crear} {$edit} title: '{$etiqueta}', width: '{$colCount}%', type: 'checkbox',values: { '0': 'No', '1': 'Si' }{$default}}";
                break;
            case 'DECIMAL':
            case 'FLOAT':
                $val = "," . $this->genValidateOptions($nulo, 'number');
                $out = $nTabs . "{$nombre}:{ {$list} {$crear} {$edit} title: '{$etiqueta}', width: '{$colCount}%'{$default}{$val}}";
                break;
            case 'MONEY':
                $val = "," . $this->genValidateOptions($nulo, 'currency');
                $out = $nTabs . "{$nombre}:{ {$list} {$crear} {$edit} title: '{$etiqueta}', width: '{$colCount}%'{$default}{$val}}";
                break;
            case 'INTEGER':
            case 'LOOKUPINT':
                $val = "," . $this->genValidateOptions($nulo, 'number');
                $out = $nTabs . "{$nombre}:{ {$list} {$crear} {$edit} title: '{$etiqueta}', width: '{$colCount}%'{$default}{$val}}";
                break;
            case 'DATE':
            case 'DATETIME':
                $val = "," . $this->genValidateOptions($nulo, '');
                $out = $nTabs . "{$nombre}: { {$list} {$crear} {$edit} title: '{$etiqueta}', type: 'date', displayFormat: 'dd-MM-YYYY',width: '{$colCount}%'{$default}{$val}}";
                break;
            case 'TEXT':
            case 'TEXT(250)':
            case 'TEXT(500)':
            case 'TEXT(750)':
            case 'TEXT(1000)':
            case 'TEXT(1500)':
            case 'TEXT(2000)':
            case 'TEXT(2500)':
                $val = "," . $this->genValidateOptions($nulo, '');
                $out = $nTabs . "{$nombre}: { type: 'textarea', {$list} {$crear} {$edit} title: '{$etiqueta}', width: '{$colCount}%'{$default}{$val}}";
                break;
            case 'VARCHAR':
            case 'LOOKUPVAR':
            case 'PASSWORD':
                $val = "," . $this->genValidateOptions($nulo, '');
                $out = $nTabs . "{$nombre}:{ {$list} {$crear} {$edit} title: '{$etiqueta}', width: '{$colCount}%'{$default}{$val}}";
                break;
            case 'OBJECT':
                $val = "," . $this->genValidateOptions($nulo, '');
                $out = $nTabs . "{$nombre}: { list: false, create: false, edit: false, title: '{$etiqueta}', width: '{$colCount}%'{$default}{$val}}";
                break;
            case 'EMAIL':
                $val = "," . $this->genValidateOptions($nulo, 'email');
                $out = $nTabs . "{$nombre}: { {$list} {$crear} {$edit} title: '{$etiqueta}', width: '{$colCount}%'{$default}{$val}}";
                break;
            case 'IMAGE':
                $out  = $nTabs . "{$nombre}: {\n";
                $out .= $nTabs . "    title: '{$etiqueta}',\n";
                $out .= $nTabs . "    type: 'file',\n";
                $out .= $nTabs . "    create: false,\n";
                $out .= $nTabs . empty($edit) ? '' : "    {$edit}";
                $out .= $nTabs . empty($list) ? '' : "    {$list}";
                $out .= $nTabs . "    width: '{$colCount}%',\n";
                $out .= $nTabs . "    listClass: \"class-row-center\",\n";
                $out .= $nTabs . "    display: function(data){\n";
                $out .= $nTabs . "        if(data.record.{$nombre} + '' != 'undefined' && data.record.{$nombre}_name != 'null' && data.record.{$nombre}_name != null)\n";
                $out .= $nTabs . "            return '<a href=\"' + '<?= strtolower(GALLERY_PATH);?>' + data.record.{$nombre}_name +  '\"><img src=\"' + '<?= strtolower(GALLERY_PATH);?>' + data.record.{$nombre}_name +  '\" width=\"40\" height=\"56\" class=\"preview\"></a>';\n";
                $out .= $nTabs . "        else\n";
                $out .= $nTabs . "            return 'No cargada';\n";
                $out .= $nTabs . "    },\n";
                $out .= $nTabs . "    input: function(data){\n";
                $out .= $nTabs . "            return '<a href=\"' + '<?= strtolower(GALLERY_PATH);?>' + data.record.{$nombre}_name +  '\"><img src=\"' + '<?= strtolower(GALLERY_PATH);?>' + data.record.{$nombre}_name +  '\" width=\"40\" height=\"56\" class=\"preview\"></a>';\n";
                $out .= $nTabs . "    }\n";
                $out .= $nTabs . "},\n";
                $out .= $nTabs . "{$nombre}_upl: {\n";
                $out .= $nTabs . "    title: 'Seleccione',\n";
                $out .= $nTabs . "    list: false,\n";
                $out .= $nTabs . "    create: true,\n";
                $out .= $nTabs . "    input: function(data) {\n";
                $out .= $nTabs . "        html = '<input type =\"file\" id=\"{$nombre}_upl\" name=\"{$nombre}_upl\" accept=\"image/*\" />';\n";
                $out .= $nTabs . "        return html;\n";
                $out .= $nTabs . "    }\n";
                $out .= $nTabs . "}\n";
                break;
            case 'ENUM':
                $val = $this->genValidateOptions($nulo, '');
                $out  = $nTabs . "{$nombre}: {\n{$nTabs}{$list}\n{$nTabs}{$edit}\n";
                $out .= $nTabs . "    title: '{$etiqueta}',\n";
                $out .= $nTabs . "    options: function(data) {\n";
                $out .= $nTabs . "        if (data.source == 'list') {\n";
                $out .= $nTabs . "            return '<?=HOME?>/Combo/comboOptions/name={$nombre}';\n";
                $out .= $nTabs . "        }\n";
                $out .= $nTabs . "        return '<?=HOME?>/Combo/comboOptions/name={$nombre}';\n";
                $out .= $nTabs . "    }";
                $out .= !empty($default) ? "\n{$nTabs}{$default}" : '';
                $out .= !empty($val) ? ",\n{$nTabs}{$val}" : '';
                $out .= $nTabs . "}";
                break;
            case 'ENUMINT': // set "mostrar" = 0 to set it as a dependency combo
                $val = $this->genValidateOptions($nulo, 'integer');
                if (strpos($externo, '|') !== false) {
                    list($extTable, $extField) = explode('|', $externo);
                    $obj = !empty($table) ? $table : $tableName;
                    if ($extTable === '[FATHER]') {
                        $ext = $this->model->toCamelCase($obj, true);
                    } else {
                        $ext = $this->model->toCamelCase($extTable, true);
                    }
                    $out  = $nTabs . "{$nombre}: {\n{$nTabs}{$list}\n{$nTabs}{$edit}\n";
                    $out .= $nTabs . "    title: '{$etiqueta}',\n";
                    $out .= $nTabs . "    options: function(data) {\n";
                    $out .= $nTabs . "        if (data.source == 'list') {\n";
                    $out .= $nTabs . "            return '<?=HOME?>/" . $ext . "/comboSearch" . $ext . "/extfield_name={$extField}';\n";
                    $out .= $nTabs . "        }\n";
                    $out .= $nTabs . "        return '<?=HOME?>/" . $ext . "/comboSearch" . $ext . "/extfield_name={$extField}';\n";
                    $out .= $nTabs . "    }\n";
                    $out .= !empty($default) ? "\n{$nTabs}{$default}" : '';
                    $out .= !empty($val) ? ",\n{$nTabs}{$val}" : '';
                    $out .= $nTabs . "}";
                } else {
                    $out .= "";
                    $this->message .= "Corregir dato \"externo\" en la columna: {$nombre}\n";        
                }
                break;
            case 'ENUMDEPENDINT':
                $val = $this->genValidateOptions($nulo, 'integer');
                if (strpos($externo, '|') !== false) {
                    list($extTable, $extField) = explode('|', $externo);
                    $obj = !empty($table) ? $table : $tableName;
                    if ($extTable === '[FATHER]') {
                        $ext = $this->model->toCamelCase($obj, true);
                    } else {
                        $ext = $this->model->toCamelCase($extTable, true);
                    }
                    $out  = $nTabs . "{$nombre}: {\n{$nTabs}{$list}\n{$nTabs}{$edit}\n";
                    $out .= $nTabs . "    title: '{$etiqueta}',\n";
                    $out .= $nTabs . "    dependsOn: '{$clave}',\n";
                    $out .= $nTabs . "    options: function(data) {\n";
                    $out .= $nTabs . "        if (data.source == 'list') {\n";
                    $out .= $nTabs . "            return '<?=HOME?>/{$ext}/comboSearch{$ext}/{$clave}=0&extfield_name={$extField}';\n";
                    $out .= $nTabs . "        }\n";
                    $out .= $nTabs . "        return '<?=HOME?>/{$ext}/comboSearch{$ext}/{$clave}=' + data.dependedValues.{$clave} + '&extfield_name={$extField}';\n";
                    $out .= $nTabs . "    }\n";
                    $out .= !empty($default) ? "\n{$nTabs}{$default}" : '';
                    $out .= !empty($val) ? ",\n{$nTabs}{$val}" : '';
                    $out .= $nTabs . "}";
                } else {
                    $out .= "";
                    $this->message .= "Corregir dato \"externo\" en la columna: {$nombre}\n";        
                }
                break;
            case 'ENUMFOLDER':
                $val = $this->genValidateOptions($nulo, 'onlyLetterSp');
                $obj  = !empty($tableName) ? $tableName : $table;
                $out  = $nTabs . "{$nombre}: {\n{$nTabs}{$list}\n{$nTabs}{$edit}\n";
                $out .= $nTabs . "    title: '{$etiqueta}',\n";
                $out .= $nTabs . "    options: function(data) {\n";
                $out .= $nTabs . "        if (data.source == 'list') {\n";
                $out .= $nTabs . "           return '<?=HOME?>/" . $this->model->toCamelCase($obj, true) . "/comboFolderOptions';\n";
                $out .= $nTabs . "       }\n";
                $out .= $nTabs . "       return '<?=HOME?>/" . $this->model->toCamelCase($obj, true) . "/comboFolderOptions';\n";
                $out .= $nTabs . "    }\n";
                $out .= !empty($default) ? $nTabs . "\n{$nTabs}{$default}" : $nTabs . ",defaultValue: '" . $this->model->toCamelCase($table, true) . "'";
                $out .= !empty($val)     ? $nTabs . ",\n{$nTabs}{$val}" : '';
                $out .= $nTabs . "}";
                break;
            case 'CHILD':
                $out = $this->childContent($nombre, $etiqueta, $table, $titulo, $icono, $clave, $nombre);
                break;
        }
        return $out;
    }
    
    private function genLanguagesButton($tableName, $nTabs)
    {
        $out  = $nTabs . "<?php if(LANG_SWITCH === true): ?>\n";
        $out .= $nTabs . ",\n";
        $out .= $nTabs . "btn_language: {\n";
        $out .= $nTabs . "    title: 'Idiomas',\n";
        $out .= $nTabs . "    width: '5%',\n";
        $out .= $nTabs . "    create: false,\n";
        $out .= $nTabs . "    edit: false,\n";
        $out .= $nTabs . "    display: function(data) {\n";
        $out .= $nTabs . "        dataToSend = \"{id : \" + data.record.id + \", name: \'\" + data.record.nombre + \"\'}\";\n";
        $out .= $nTabs . "        return '<button type=\"button\" class=\"button success outline small\" onclick=\"searchTranslationFields(homePath, &quot;{$tableName}&quot;,' + data.record.id + ');\">Idiomas</button>';\n";
        $out .= $nTabs . "    }\n";
        $out .= $nTabs . "}\n";
        $out .= $nTabs . "<?php endif;?>\n";
        return $out;
    }
    
    private function iterateSearchableCols($tableId)
    {
        $out       = "";
        $content   = "";
        $aRows     = $this->column->findSearchableColsByTableId($tableId);
        $countRows = count($aRows);
        if ($countRows > 0) {
            $content .= "                        <div class=\"w-25 d-inline-block\">\n";
            $content .= "                            <select id=\"field\" data-role=\"select\" data-empty-value data-prepend=\"Filtro\">\n";
            $content .= "                                <option value=\"\"> --------------- </option>\n";
            foreach ($aRows as $row) {
                $content .= "                                <option value=\"" . $row['nombre'] . "\">" . $row['etiqueta'] . "</option>\n";
            }
            $content .= "                            </select>\n";
            $content .= "                        </div>\n";
            $content .= "                        <div class=\"d-inline-block pt-0 mt-0 pl-2\">\n";
            $content .= "                            <input type=\"text\" name=\"value\" id=\"value\" data-role=\"input\" data-prepend=\"Valor\"/>\n";
            $content .= "                        </div>\n";
            $out = $this->genSearchDiv($content);
        }
        return $out;
    }
    
    private function genSearchDiv($options)
    {
        $content = "                 <form method=\"POST\" class=\"\">\n";
        $content .= "                    <div class=\"filtering w-70 d-flex flex-justify-center mt-5\">\n";
        $content .= $options;
        $content .= "                        <div class=\"d-inline-block pl-2\"><button class=\"button success\" type=\"submit\" id=\"search\">Buscar</button></div>\n";
        $content .= "                        <div class=\"d-inline-block pl-2\"><button class=\"button alert\" id=\"clean\">Limpiar</button></div>\n";
        $content .= "                    </div>\n";
        $content .= "                </form>\n";
        return $content;
    }

}
