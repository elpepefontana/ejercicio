<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface;
use System\Core\Controls;

class Table extends AbstractControl implements ControlInterface
{
    
    private $headers;
    private $data;
    private $isGrid;
    
    public function __construct(array $panelData)
    {
        $this->setData($panelData);
    }
    
    public function render(): string
    {
        if (!$this->isGrid) {
            return $this->drawCompleteTable();
        } 
        return $this->draw();
    }
    
    private function setData($panelData)
    {
        foreach ($panelData as $key => $data) {
            if (!property_exists(__CLASS__, $key)) {
                continue;
            }
            $this->$key = $data;
        }
    }
        
    public function draw(): string 
    {
        $objName    = $this->name;
        $this->name = $this->name  !== '' ? "id=\"tbl_{$this->name}\" name=\"tbl_{$this->name}\"" : '';
        $this->css_class = $this->choseTableClass($this->css_class);
        if (!is_array($this->content)) {
            return '';
        }
        $this->attributes = $this->choseTableAttributes($this->attributes);
        $content = "<table {$this->name} class=\"{$this->css_class} compact\" {$this->attributes} w-100>\n";
        $title   = array_column($headers['Records'], 'etiqueta');
        $cols    = array_column($headers['Records'], 'nombre'); 
        $type    = array_column($headers['Records'], 'tipo');
        $icon    = array_column($headers['Records'], 'icono');
        if ($withHeaders === true) {
            $content .= "    <thead>\n";
            $content .= "        <tr>\n";
            $x = 0;
            $content .= "            <th data-name=\"id\" data-show=\"false\">Id</th>\n";
            foreach ($title as $header) {
                if ($type[$x] === 'CHILD')  
                    $content .= "            <th data-name=\"{$cols[$x]}\">{$header}</th>\n";
                else
                    $content .= "            <th class=\"sortable-column sort-asc\" data-name=\"{$cols[$x]}\">{$header}</th>\n";
                $x += 1;
            }
            if ($isAdmin) {
                if (LANG_SWITCH) {
                    $content .= "            <th>Idiomas</th>\n";
                }
                $content .= "            <th data-cls-column=\"text-center\">Editar</th>\n";
                $content .= "            <th data-cls-column=\"text-center\">Borrar</th>\n";
            }
            $content .= "        </tr>\n";
            $content .= "    </thead>\n";
        }
        $content .= "    <tbody>\n";
        $z = 0;
        foreach ($this->content['Records'] as $data) {
            $content .= "        <tr>\n";
            $content .= "             <td>" . $data['id'] . "</td>\n";
            $i = 0;
            foreach ($cols as $col) {
                if ($type[$i] === 'CHILD' && $isAdmin)
                    $content .= "             <td><a href=\"javascript:openGrid(homePath, '{$col}', true)\"><span class=\"{$icon[$i]} fg-darkBlue\"></span></a></td>\n";
                else
                    $content .= "             <td>" . $data[$col] . "</td>\n";
                $i += 1;
            }
            if ($isAdmin) {
                if (LANG_SWITCH) {
                    $btn = Controls::drawButton('btn_tran_' . $data['id'], 'success outline small', 'Traducir', " onclick=\"searchTranslationFields(homePath, 'membership', {$data['id']});\" ");    
                    $content .= "              <td>{$btn}</td>\n";
                }
                $content .= "             <td><a href=\"javascript:openNewEditModal(homePath, 'change', '{$objName}', '{$objName}', headers, '{$data['id']}');\"><span class=\"mif-pencil mif-2x fg-green\"></span></a></td>\n";
                $content .= "             <td><a href=\"javascript:confirmDelete(homePath, '{$objName}', headers, '{$data['id']}')\"><span class=\"mif-bin mif-2x fg-red\"></span></a></td>\n";
            }
            $content .= "        </tr>\n";
            $z += 1;
        }
        $content .= "    </tbody>\n";
        $content .= "</table>\n";
        return $content;
    }
    
    public function drawCompleteTable() : string 
    {
        
        if (!empty($this->tableHeaders) && !empty($this->tableContent)) {
            $tableDef = $this->draw($this->name, 'exportinspector', $this->tableHeaders, $$this->tableContent, 'exportinspector', true, true);
        }
        $content       = '';
        $check1        = Controls::drawCheckBox('', '', "data-caption=\"Nro linea\" onclick=\"$('#tbl_{$this->name}').attr('data-rownum', $(this).is(':checked'))\"") . "\n";
        $check2        = Controls::drawCheckBox('', '', "data-caption=\"Casillas\" checked onclick=\"$('#tbl_{$this->name}').attr('data-check', $(this).is(':checked'))\"") . "\n";
        $content      .= Controls::drawDiv('', 'w-100', $check1 . $check2, '');
        $tableSearch   = Controls::drawDiv("{$this->name}-search", '', '', '');
        $searchDiv     = Controls::drawDiv('', 'w-75', $tableSearch, '');
        
        $listContent   = "<ul class=\"d-menu context shadow-3\" data-role=\"dropdown\">\n";
        $listContent  .= "    <li><a href=\"#\" onclick=\"$('#tbl_{$this->name}').data('table').export('CSV', 'all', 'Exportar todos.csv')\"><span class=\"mif-upload2 fg-cyan icon\"></span> Exportar todos</a></li>\n";
        $listContent  .= "    <li><a href=\"#\" onclick=\"$('#tbl_{$this->name}').data('table').export('CSV', 'all-filtered', 'Exportar con filtro.csv')\"><span class=\"mif-upload2 fg-cyan icon\"></span> Exportar filtrados</a></li>\n";
        $listContent  .= "    <li><a href=\"#\" onclick=\"$('#tbl_{$this->name}').data('table').export('CSV', 'checked', 'Exportar seleccionados.csv')\"><span class=\"mif-upload2 fg-steel icon\"></span> Exportar seleccionados</a></li>\n";
        $listContent  .= "    <li><a href=\"#\" onclick=\"$('#tbl_{$this->name}').data('table').export('CSV', 'view', 'Exportar vista.csv')\"><span class=\"mif-upload2 fg-brown icon\"></span> Exportar vista actual</a></li>\n";
        $listContent  .= "</ul>\n";
        
        $button        = Controls::drawButton("", "button", "<span class=\"mif-more-horiz\"></span>", '');
        $link          = Controls::drawLink("{$this->name}_configCog", '', 'button ml-1', '<span class="mif-cog"></span>', "onclick=\"$('#tbl_{$this->name}').data('table').toggleInspector()\"");
        $dropDown      = Controls::drawDiv('', 'dropdown-button pr-20', $button . $listContent . $link . "", '');
        $tableAction   = Controls::drawDiv('table-actions', 'd-flex flex-justify-start', $dropDown, '');
        $actionDiv     = Controls::drawDiv('', 'no-wrap ml-1', $tableAction, '');
        
        $newButton     = Controls::drawImageButton("", "success w-75 mt-1 ml-10 ", "<span class=\"mif-plus icon\"></span><span class=\"caption text-center\">Nuevo registro</span>", "", '');
        $newButtonDiv  = Controls::drawDiv('', 'w-25', $newButton, "onclick=\"openNewEditModal('create', '{$this->name}', '', 'modals');\"");
        $content      .= Controls::drawDiv('', 'row w-100 flex-justify-between flex-nowrap ml-0 mr-0 mt-2', $searchDiv . $newButtonDiv . $actionDiv, ''); 
        
        $pagination    = Controls::drawDiv("{$this->name}-pagination", '', '', '');
        $info          = Controls::drawDiv("{$this->name}-info", 'p-2', '', '');
        $cell1         = Controls::drawDiv('', 'cell-md-8', $pagination . $info, '');
        $count         = Controls::drawDiv("{$this->name}-count", '', '', '');
        $cell2         = Controls::drawDiv('', 'cell-md-4', $count, ''); 
        $rowDiv        = Controls::drawDiv('', 'row',  $cell1 . $cell2, '');
        $content      .= Controls::drawDiv("{$this->name}_container", '', $tableDef, '') . $rowDiv;
        return $content;
    }
    
    private function defaultTableClass()
    {
        return "table compact striped table-border mt-4";
    }
    
    private function exportInspectorClass()
    {
        return "table table-border row-border striped row-hover mt-4 entities-table sortable-markers-on-left"; 
    }
    
    private function choseTableClass($type)
    {
        switch ($type) {
            case 'default':
                return self::defaultTableClass();
            case 'exportinspector':
                return self::exportInspectorClass();
            default:
                return self::defaultTableClass();
        }
    }
    
    private function choseTableAttributes($type)
    {
        switch ($type) {
            case 'simple':
                return self::simpleTableAttributes();
            case 'exportinspector':
                return self::exportInspectorAttributes();
            case '':    
                return self::defaultTableAttributes();
            default:
                return $type;
        }
    }
    
    private function defaultTableAttributes()
    {
        return "data-role=\"table\"
                data-rownum=\"true\"
                data-horizontal-scroll=\"true\"
                data-table-rows-count-title=\"Mostrar\"
                data-table-search-title=\"Buscar\"
                data-table-info-title=\"Mostrando $1 a $2 de $3 registros\"
                data-pagination-prev-title=\"Anterior\"
                data-pagination-next-title=\"Siguiente\"
                data-all-records-title=\"Todos\"
                ";
    }
    
    private function simpleTableAttributes()
    {
        return "data-role=\"table\"
                data-rownum=\"true\"
                data-horizontal-scroll=\"true\"
                data-rownum=\"true\"
                data-show-rows-steps=\"false\"
                data-show-search=\"false\"
                data-show-table-info=\"false\"
                data-show-pagination=\"false\"
                data-rows=\"-1\"
                ";
    }
    
    private function exportInspectorAttributes()
    {
        return "style=\"width: 100%; min-width: 300px!important;\"
                data-pagination-prev-title=\"Anterior\"
                data-pagination-next-title=\"Siguiente\"
                data-role=\"table\"
                data-cls-table-top=\"row\"
                data-cls-search=\"cell-md-12\"
                data-cls-rows-count=\"cell-md-4\"
                data-table-search-title=\"Buscar:\"
                data-table-rows-count-title=\"Mostrar:\"
                data-table-info-title=\"Mostrando $1 a $2 de $3 registros\"
                data-all-records-title=\"Todos\"
                data-horizontal-scroll=\"true\"
                data-rows=\"10\"
                data-rows-steps=\"-1, 10, 20, 50, 100\"
                data-info-wrapper=\"#table-info\"
                data-pagination-wrapper=\"#pagination\"
                data-rows-wrapper=\"#rows-count\"
                data-search-wrapper=\"#table-search\"
                data-inspector-title=\"Configuración\"
                data-inspector-save-title=\"Guardar\"
                data-on-draw-cell=\"tableFuncs.onDrawCell\"
                data-on-draw-row=\"tableFuncs.onDrawRow\"
                data-view-save-mode=\"client\"
                data-check=\"true\"
                data-check-col-index=\"0\"
                data-check-store-key=\"MY_STORE_FOR_TABLE:$1\"";
    }
}
