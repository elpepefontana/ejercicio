<?php

namespace System\Core;


class Controls 
{
    
    /******************************************************************************************************
    *         
    *           FUNCIONES DE MENU
    *  
    *******************************************************************************************************/
    
    //************************************** MENU *******************************************
    
    public static function drawMenu($menuKind, $menuJson, $attributes = '', $home, $exit, $shadow = true)
    {
        $menu = json_decode($menuJson, false);
        if (!is_object($menu)) { 
            return ''; 
        }
        $menuKind = $menuKind !== '' ? $menuKind : 'h-menu';
        $shadow   = $shadow === true ? 'drop-shadow' : '';
        $content  = "<ul class=\"{$menuKind} $shadow bg-{$menu->back_color} fg-{$menu->font_color} py-2 px-2\" {$attributes} >\n";
        $content .= $home === true ? "   <li><a href=\"" . HOME . "/AdminHome\"><span class=\"icon mif-home fg-{$menu->font_color}\" ></span></a></li>\n" : '';
        if (is_array($menu->groups) && count($menu->groups) > 0 ) {
            $groups = $menu->groups;
            foreach ($groups as $group) {
                $group->back_color =  $menu->back_color; 
                $group->font_color =  $menu->font_color;
                if (!is_array($group->items) || count((array)$group->items) == 0) {
                    $content .= "    <li><a href=\"" . HOME . "/{$group->link}\"><span class=\"icon mif-{$group->icon} fg-{$group->font_color} pl-4 pr-4\" ></span>{$group->title}</a></li>\n";
                    continue;
                }
                $content .= "<li>";
                $content .= "    <a href=\"#\" class=\"dropdown-toggle\">{$group->title}</a>\n";
                $content .= "    <ul class=\"d-menu bg-{$group->back_color} fg-{$group->font_color}\" data-role=\"dropdown\">\n";
                foreach ($group->items as $item) {
                    $item->back_color = $menu->back_color; 
                    $item->font_color = $menu->font_color;
                    $content .= "               <li><a href=\"" . HOME . "/{$item->link}\"><span class=\"icon mif-{$item->icon} fg-{$item->font_color}\"></span>&nbsp;&nbsp;{$item->title}</a></li>\n";; 
                }
                $content .= "    </ul>\n";
                $content .= "</li>\n";
            }
        }
        $content .= $exit === true ? "   <li class=\"place-right\"><a href=\"" . HOME . "/Home/logOut\" class=\"app-bar-item place-right\"><span class=\"icon mif-exit fg-{$menu->font_color}\"></span></a></li>" : '';
        $content .= "</ul>";
        return $content;
    }
    
    public static function drawHome($homeJson, $home, $exit)
    {
        $menu = json_decode($homeJson, false);
        if (!is_object($menu)) {
            return ''; 
        }
        if (!is_array($menu->groups) || count((array)$menu->groups) === 0 ) {
            return '';
        }
        $groups = $menu->groups;
        $content = '';
        foreach ($groups as $group) {
            if (!is_array($group->items) || count((array)$group->items) == 0) {
                continue;
            }
            $group->back_color = !empty($group->back_color) ? $group->back_color : $menu->back_color; 
            $group->font_color = !empty($group->font_color) ? $group->font_color : $menu->font_color;
            
            $tiles = self::createHomeItems($group);
            $content .= self::drawTileGroupTitle(html_entity_decode($group->title), $tiles);
        }
        return $content;
    }
    
    private static function createHomeItems($group)
    {
        $tiles = '';
        foreach ($group->items as $item) {
            $item->back_color = !empty($item->back_color) ? $item->back_color : $menu->back_color; 
            $item->font_color = !empty($item->font_color) ? $item->font_color : $menu->font_color;
            $tiles .= Controls::drawLinkedTile(
                html_entity_decode($item->name), 
                $item->link, 
                "bg-{$item->back_color} fg-{$item->font_color}", 
                "mif-{$item->icon} icon", 
                html_entity_decode($item->title), 
                '', 
                $item->home_size);
        }
        return $tiles;
    }
    
    
    
    //************************************** TREEVIEW *******************************************
    
    public static function drawTreeHeader($content) 
    {
        return "<ul data-role=\"treeview\">\n{$content}\n</ul>";
    }
    
    public static function drawTreeFromJson($treeJson): string
    {
        if (is_string($treeJson)) {
            $tree = json_decode($treeJson, true);
        } elseif (is_array($treeJson)) {
            $tree = $treeJson;
        }
        if (!is_array($tree)) {
            return '';
        }
        $content = "";
        foreach ($tree as $row) {
            if ($row['name'] === 'nbproject') {
                continue;
            }
            switch ($row['type']) {
                case 'file':
                    $content .= "    <li class=\"fg-darkBlue\" data-icon=\"<span class='mif-file-empty fg-darkBlue'></span>\" data-caption=\"{$row['name']}\"></li>\n";
                    break;
                case 'folder':
                    $items = $row['items'];
                    $content .= "    <li class=\"fg-dark\" data-icon=\"<span class='mif-folder fg-orange'></span>\" data-caption=\"{$row['name']}\" data-collapsed=\"true\">\n"; 
                    if (!empty($items)) {
                        $content .= "        <ul>\n";
                        $content .= Controls::drawTreeFromJson($items);
                        $content .= "        </ul>\n";
                    }
                    $content .= "    </li>\n";
                    break;
            }
        }
        return $content;
    }
    
    public static function drawHtml($htmlContent)
    {
        $out  = "<!DOCTYPE HTML>\n";
        $out .= "<html>\n\t{$htmlContent}\n</html>";
        return $out;
    }
    
    public static function drawHead($headContent)
    {
        return "<head>\n\t{$headContent}\n</head>";
    }
    
    public static function drawBody($bodyContent)
    {
        return "<body>\n\t{$bodyContent}\n</body>";
    }
    
    public static function drawMain($mainName, $mainClass, $mainContent, $mainAttributes)
    {
        $mainName = !empty($mainName) ? "id=\"{$mainName}\"" : '';
        return "<main {$mainName} class=\"{$mainClass}\" {$mainAttributes}>\n{$mainContent}\n</main>\n";
    }
 
    public static function drawHeader($headerName, $headerClass, $headerContent, $headerAttributes)
    {
        $name = $headerName !== '' ? "id=\"{$headerName}\"" : '';
        return "<header {$name} class=\"{$headerClass}\" {$headerAttributes}>\n{$headerContent}\n</header>\n";
    }
    
    public static function drawNav($navName, $navClass, $navContent, $navAttributes)
    {
        $navName = $navName !== '' ? "id=\"{$navName}\"" : '';
        return "<nav {$navName} class=\"{$navClass}\" {$navAttributes}>\n{$navContent}\n</nav>\n";
    }
        
    public static function drawSection($sectionName, $sectionClass, $sectionContent, $sectionAttributes)
    {
        $sectionName = $sectionName !== '' ? "id=\"" .str_replace(' ', '_', $sectionName) . "\"" : '';
        return "<section {$sectionName} class=\"{$sectionClass}\" {$sectionAttributes}>\n{$sectionContent}\n</section>\n";
    }
    
    public static function drawArticle($articleName, $articleClass, $articleContent, $articleAttributes)
    {
        $articleName = $articleName !== '' ? "id=\"" . str_replace(' ', '–', $articleName) . "\"" : '';
        return "<article {$articleName} class=\"{$articleClass}\" {$articleAttributes}>\n{$articleContent}\n</article>\n";
    }
  
    public static function drawAside($asideName, $asideClass, $asideContent, $asideAtttributes)
    {
        $asideName = $asideName !== '' ? "id=\"{$asideName}\"" : '';
        return "<aside {$asideName} class=\"{$asideClass}\" {$asideAttributes}>\n{$asideContent}\n</aside>\n";
    }
    
    public static function drawFooter($footerName, $footerClass, $footerContent, $footerAtttributes)
    {
        $footerName = $footerName !== '' ? "id=\"{$footerName}\"" : '';
        return "<footer {$footerName} class=\"{$footerClass}\" {$footerAttributes}>\n{$footerContent}\n</footer>\n";
    }
    
    //************************************** GRID SYSTEM **************************************************/
   
    public static function drawGrid($gridName, $gridClass, $gridContent, $gridAttributes)
    {
        return Controls::drawDiv(str_replace(' ', '_', $gridName), "grid $gridClass", $gridContent, $gridAttributes);
    }
    
    public static function drawRow($rowName, $rowClass, $rowContent, $rowAttributes)
    {
        return Controls::drawDiv(str_replace(' ', '_', $rowName), "row {$rowClass}", $rowContent, $rowAttributes);
    }

    public static function drawCell($cellName, $cellClass, $cellContent, $cellAttributes, $cellCols = '', $cellOffset = '')
    {
        $cellCols   = !empty($cellCols)   ? "cell-$cellCols"     : "cell";
        $cellOffset = !empty($cellOffset) ? "offset-$cellOffset" : "";
        return Controls::drawDiv(str_replace(' ', '_', $cellName), "{$cellCols} {$cellOffset} {$cellClass}", $cellContent, $cellAttributes) . "\n";
    }
    
    //************************************** DIV **********************************************************/
    
    public static function drawDiv($divName, $divClass, $divContent, $divAttributes)
    {
        $divName = $divName !== '' ? "id=\"{$divName}\"" : '';
        return "<div {$divName} class=\"{$divClass}\" {$divAttributes}>\n{$divContent}\n</div>\n";
    }
    
    //************************************** MODAL WINDOW *************************************************/
    
    public static function drawModal(
        $modalName, 
        $modalTitle, 
        $modalTitleClass,
        $modalContent,
        $modalWidth, 
        $modalContentClass = '', 
        $modalContentAttributes = '', 
        $zIndex = '1010',
        $modalCloseObject = ''
    ) {
        if ($modalContentAttributes === '')
            $modalContentAttributes = "style=\"clear: both;overflow-x: auto;white-space: nowrap;\"";
        $width    = !empty($modalWidth) ? "width: {$modalWidth}%;" : '';
        $content  = "<div id=\"{$modalName}Modal\" class=\"Modal\" style=\"z-index: {$zIndex};\">\n";
        $content .= "    <div id=\"{$modalName}Content\" class=\"Modal-content\" style=\"{$width}\">\n";
        $content .= "        <div class=\"{$modalTitleClass}\" style=\"height: 45px;font-size: 20px; font-weight: bold\">\n";
        $content .= "            <div id=\"{$modalName}ModalTitle\" class=\"px-3 py-2 mb-3\" style=\"float: left;width: 90%\">\n";
        $content .= utf8_decode($modalTitle) . "\n";
        $content .= "            </div>\n";
        $content .= "            <div class=\"px-3\" style=\"float: left;width: 10%;\">\n";
        $content .= "                <span id=\"{$modalName}Close\" class=\"ModalClose\" onclick=\"closeModal('{$modalName}', {$modalCloseObject})\">&times;</span><p>&nbsp;</p>\n";
        $content .= "            </div>\n";
        $content .= "        </div>\n";
        $content .= "       <div id=\"{$modalName}ModalContent\" class=\"{$modalContentClass} py-3\" {$modalContentAttributes}>\n";
        $content .= $modalContent;
        $content .= "       </div>\n";
        $content .= "    </div>\n";
        $content .= "</div>\n";
        return $content;
    }
    
    //************************************** LINK *******************************************
    
    public static function drawLink($linkName, $linkUrl, $linkClass, $linkText, $linkAttribute)
    {
        $linkUrl  = !empty($linkUrl) ? "href=\"{$linkUrl}\"" : "";
        $linkName = !empty($linkName) ? " id=\"{$linkName}\" name=\"{$linkName}\"" : "";
        return "<a {$linkUrl} {$linkName} class=\"{$linkClass}\" {$linkAttribute}>{$linkText}</a>";
    }
    
    //************************************** TABLES *******************************************
   
    public static function drawCompleteTable(string $tableName, string $fatherName, bool $withTable, array $tableHeaders, array $tableContent) : string 
    {
        
        $tableDef = $withTable ? self::drawArrayToTable($tableName, 'exportinspector', $tableHeaders, $tableContent, 'exportinspector', true, true) : '';
        $content       = '';
        $check1        = self::drawCheckBox('', '', "data-caption=\"Nro linea\" onclick=\"$('#tbl_{$tableName}').attr('data-rownum', $(this).is(':checked'))\"") . "\n";
        $check2        = self::drawCheckBox('', '', "data-caption=\"Casillas\" checked onclick=\"$('#tbl_{$tableName}').attr('data-check', $(this).is(':checked'))\"") . "\n";
        $content      .= self::drawDiv('', 'w-100', $check1 . $check2, '');
        $tableSearch   = self::drawDiv("{$tableName}-search", '', '', '');
        $searchDiv     = self::drawDiv('', 'w-75', $tableSearch, '');
        
        $listContent   = "<ul class=\"d-menu context shadow-3\" data-role=\"dropdown\">\n";
        $listContent  .= "    <li><a href=\"#\" onclick=\"$('#tbl_{$tableName}').data('table').export('CSV', 'all', 'Exportar todos.csv')\"><span class=\"mif-upload2 fg-cyan icon\"></span> Exportar todos</a></li>\n";
        $listContent  .= "    <li><a href=\"#\" onclick=\"$('#tbl_{$tableName}').data('table').export('CSV', 'all-filtered', 'Exportar con filtro.csv')\"><span class=\"mif-upload2 fg-cyan icon\"></span> Exportar filtrados</a></li>\n";
        $listContent  .= "    <li><a href=\"#\" onclick=\"$('#tbl_{$tableName}').data('table').export('CSV', 'checked', 'Exportar seleccionados.csv')\"><span class=\"mif-upload2 fg-steel icon\"></span> Exportar seleccionados</a></li>\n";
        $listContent  .= "    <li><a href=\"#\" onclick=\"$('#tbl_{$tableName}').data('table').export('CSV', 'view', 'Exportar vista.csv')\"><span class=\"mif-upload2 fg-brown icon\"></span> Exportar vista actual</a></li>\n";
        $listContent  .= "</ul>\n";
        
        $button        = self::drawButton("", "button", "<span class=\"mif-more-horiz\"></span>", '');
        $link          = self::drawLink("{$tableName}_configCog", '', 'button ml-1', '<span class="mif-cog"></span>', "onclick=\"$('#tbl_{$tableName}').data('table').toggleInspector()\"");
        $dropDown      = self::drawDiv('', 'dropdown-button pr-20', $button . $listContent . $link . "", '');
        $tableAction   = self::drawDiv('table-actions', 'd-flex flex-justify-start', $dropDown, '');
        $actionDiv     = self::drawDiv('', 'no-wrap ml-1', $tableAction, '');
        
        $newButton     = self::drawImageButton("", "success w-75 mt-1 ml-10 ", "<span class=\"mif-plus icon\"></span><span class=\"caption text-center\">Nuevo registro</span>", "", '');
        $newButtonDiv  = self::drawDiv('', 'w-25', $newButton, "onclick=\"openNewEditModal('create', '{$tableName}', '', 'modals');\"");
        $content      .= self::drawDiv('', 'row w-100 flex-justify-between flex-nowrap ml-0 mr-0 mt-2', $searchDiv . $newButtonDiv . $actionDiv, ''); 
        
        $pagination    = self::drawDiv("{$tableName}-pagination", '', '', '');
        $info          = self::drawDiv("{$tableName}-info", 'p-2', '', '');
        $cell1         = self::drawDiv('', 'cell-md-8', $pagination . $info, '');
        $count         = self::drawDiv("{$tableName}-count", '', '', '');
        $cell2         = self::drawDiv('', 'cell-md-4', $count, ''); 
        $rowDiv        = self::drawDiv('', 'row',  $cell1 . $cell2, '');
        $content      .= self::drawDiv("{$tableName}_container", '', $tableDef, '') . $rowDiv;
        return $content;
    }
        
    public static function drawArrayToTable(
            string $tableName, 
            string $tableClass, 
            array  $tableHeaders, 
            array  $tableContent = [], 
            string $tableAttributtes, 
            bool   $withHeaders = true, 
            bool   $isAdmin = false
    ): string {
        $objName    = $tableName;
        $tableName  = $tableName  !== '' ? "id=\"tbl_{$tableName}\" name=\"tbl_{$tableName}\"" : '';
        $tableClass = self::choseTableClass($tableClass);
        if (!is_array($tableContent)) {
            return '';
        }
        $tableAttributtes = self::choseTableAttributes($tableAttributtes);
        $content = "<table {$tableName} class=\"{$tableClass} compact\" {$tableAttributtes} w-100>\n";
        $title   = array_column($tableHeaders['Records'], 'etiqueta');
        $cols    = array_column($tableHeaders['Records'], 'nombre'); 
        $type    = array_column($tableHeaders['Records'], 'tipo');
        $icon    = array_column($tableHeaders['Records'], 'icono');
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
        foreach ($tableContent['Records'] as $data) {
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
                    $btn = self::drawButton('btn_tran_' . $data['id'], 'success outline small', 'Traducir', " onclick=\"searchTranslationFields(homePath, 'membership', {$data['id']});\" ");    
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
    
    public static function defaultTableClass()
    {
        return "table compact striped table-border mt-4";
    }
    
    public static function exportInspectorClass()
    {
        return "table table-border row-border striped row-hover mt-4 entities-table sortable-markers-on-left"; 
    }
    
    public static function choseTableClass($type)
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
    
    public static function choseTableAttributes($type)
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
    
    public static function defaultTableAttributes()
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
    
    public static function simpleTableAttributes()
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
    
    public static function exportInspectorAttributes()
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
    
    //************************************** LISTS *******************************************
    
    public static function drawArrayList($listName, $listContent)
    {
        $listName = $listName !== '' ? "id=\"{$listName}\" name=\"{$listName}\"" : '';
        $content  = "<ul id=\"{$listName}\" name=\"{$listName}\" 
                    data-role=\"list\" 
                    data-show-search=\"true\" 
                    data-cls-list=\"unstyled-list row flex-justify-center mt-4\"
                    data-cls-list-item=\"cell-sm-6 cell-md-4\" class=\"text-center\">\n";
        $content .= $listContent;
        $content .= "</ul>\n";
        return $content;
    }
    
    //************************************** FIGURE *******************************************
    
    public static function drawFigure($imageName, $imagePath, $imageClass, $caption, $captionAlign, $imageType = 'general')
    {
        $content  = "<figure>/n";
        if ($imageType === 'general')
            $content .= Controls::drawImage($imageName, $imagePath, $imageClass);
        else {
            $fm = new FileManager();
            $image    = $fm->createGdImage($imagePath);
            $content .= Controls::drawGDImage($imageName, $image, $imageClass);
            $image    = null;
        }
        if (!empty($caption) && !is_array($caption)) { 
            $content .= "<figcaption>{$caption}</figcaption>/n"; 
        } elseif (!empty($caption) && is_array($caption)) {
            foreach ($caption as $c) {    
                $content .= !empty($captionAlign) ? "    <figcaption class=\"{$captionAlign}\">/n" : "<figcaption>/n";
                $content .= "        {$c}/n";
                $content .= "    </figcaption>/n";
            }
        }
        $content .= "</figure>/n";
        return $content;
    }
    
    //************************************** IMAGE *******************************************
    
    public static function  drawImage($imageName, $imagePath, $imageClass, $attributes, $imageWidth = '', $imageHeight = '', $overlay = '')
    {
        $imagePath = strtolower($imagePath);
        $width  = !empty($imageWidth) ? "width: $imageWidth;" : '';
        $height = !empty($imageHeight) ? "height: $imageHeight;" : '';
        $style  = !empty($width) && !empty($height) ? "style=\"{$width}{$height}\"" : '';
        $content  = "<div id=\"img_{$imageName}\" class=\"img-container {$imageClass}\" {$attributes}>\n";
        $content .= "   <img src=\"{$imagePath}{$imageName}\" data-src=\"{$imagePath}{$imageName}\" {$style}>\n";
        $content .= !empty($overlay) ? self::drawImageOverLay($overlay) : '';
        $content .= "</div>\n";
        return $content;
    }
    
    public static function  drawImageMagnifier($imageName, $imagePath, $imageClass, $attributes)
    {
        $imagePath = strtolower($imagePath);
        $content  = "<div class=\"imagemagnifier{$imageClass}\" data-magnifier-mode=\"glass\" data-lens-type=\"circle\" data-lens-size=\"200\" {$attributes}>\n";
        $content .= "   <img class=\"h-100\" src=\"{$imagePath}{$imageName}\">\n";
        $content .= "</div>\n";
        return $content;
    }
    
    public static function drawImageDisplay(array $aImages, array $config): string 
    {
        // lines, path, link, bg-color, fg-color, x, y, border, cols
        $rows  = '';
        $baseX = 2;
        $baseY = 3;
        $x     = !empty($config['x']) || $config['x'] != 0 ? ($baseX + $config['x']) : $baseX;
        $aColors = self::setColors($config['bg-color'], $config['fg-color']);
        $aToDraw = self::genImgArray($aImages, $config['lines']);
        is_array($aToDraw) ? extract($aToDraw) : '';
        for ($a = 0; $a < count($pics); $a++) {
            $line = '';
            $switch = $a % 2 > 0 ? false : true;
            for ($i = 0; $i < count($pics[$a]); $i++) {
                $img = $pics[$a][$i];
                $frm = $frame[$a][$i];
                $ide = $id[$a][$i];
                $tit = $title[$a][$i];
                $des = $desc[$a][$i];
                $tot = $total[$a][$i];
                $sof = $soft[$a][$i];
                $har = $all[$a][$i];
                $shadow    = $i % 2 > 0 ? '' : '';
                $aYValues  = self::setYValues($i, $baseY, $config['y'], $switch);
                $imgBorder = $config['panel'] === false ? '' : '';
                $image     = self::drawImage($img, $config['path'], '', 'style="background-color:rgb(245,245,220,0.3)"', '', '', ' ');
                if (!empty($config['link'])) {
                    $val  = strpos($config['link'], 'renderItemView') !== false ? '&picpos=' . $i ."&frame={$frm}" : '';
                    $url  = str_replace('[replace]', $ide, $config['link']) . $val;
                    $link = self::drawLink('', $url, '', $image, 'style="background-color:rgb(245,245,220,0.3)"');
                } else
                    $link = $image;
                if ($config['panel'] === true) {
                    $linkDiv = self::drawDiv('', '', $link, 'style="display: table-cell;width:50%;""');
                    $innerPanel = self::drawGalleryDetails($tit, $des, '<b>Total:</b> ' . $tot . '<br/><b>Soft:</b> ' . $sof . ' / <b>Hard:</b> ' . $har .'');
                    $panel = self::drawDiv('', $shadow . '', $linkDiv . $innerPanel, 'style="display: table;"');
                } else
                    $panel = $link; 
                $cols = $config['cols'];
                $line .= self::drawCell("cell_" . $i, "px-{$x} pt-{$aYValues['t']} pb-{$aYValues['b']}", $panel, '', $cols);
            }
            $style = !empty($aColors['style']) ? 'style="' . $aColors['style'] . '"' : '';
            $rows .= self::drawRow("row_" . $a, $aColors['class'], $line, $style);
        }
        $content = self::drawGrid('pics', '', $rows, '');
        return $content;
    }
    
    public static function setYPadding($y, $baseY)
    {
        return !empty($y) || $y != 0 ? ($y + $baseY) : $baseY;
    }
    
    public static function drawImageOverLay($ImageOverlayContent)
    {
        $content  = '<div class="image-overlay op-white">';
        $content .= $ImageOverlayContent; 
        $content .= '</div>';
        return $content;
    }
    
    public static function drawGalleryDetails($title, $description, $count)
    {
        $head    = self::drawDiv('', 'detail_title_panel_cell', $title, 'style="display:table-cell;"');
        $headRow = self::drawDiv('', 'detail_panel_cell', $head,'style="display:table-row;"');
        $body    = self::drawDiv('', 'detail_panel_cell', $description, 'style="display:table-cell;"');
        $bodyRow = self::drawDiv('', 'detail_panel_cell', $body,'style="display:table-row;"');
        $foot    = self::drawDiv('', 'detail_panel_cell', $count, 'style="display:table-cell;text-align: center;"');
        $footRow = self::drawDiv('', 'detail_panel_cell', $foot,'style="display:table-row;"');
        $container = self::drawDiv('', '', $headRow . $bodyRow . $footRow, 'style="display:table;height:100%"');
        return self::drawDiv('', '', $container ,'style="display:table-cell; width:50%;vertical-align:top;background-color:rgb(245,245,220,0.3)"');
    }
    
    public static function setYValues(int $iteration, int $baseY, int $y, bool $switch): array 
    {
        $it = $switch ? ($iteration + 1) : $iteration;
        if ($it % 2 > 0) {
            $t = $baseY;
            $b = $baseY + $y;
        } else {
            $t = $baseY + $y;
            $b = $baseY;
        }
        return array('t' => $t, 'b' => $b);
    }
    
    public static function setColors(string $bg,string $fg): array 
    {
        $bgColor = strpos($bg, "#") !== false ? "background-color: {$bg};" : "bg-{$bg}";
        $fgColor = strpos($fg, "#") !== false ? "color: {$fg};" : "fg-{$fg}";
        $bgColor = $bgColor === 'bg-' ? null : $bgColor; 
        $fgColor = $fgColor === 'fg-' ? null : $fgColor;
        if (strpos($bgColor, 'background-color:') !== false ){
            $fontColor = strpos($fgColor, 'color:') !== false  ? $fgColor : '';
            $style     =  "style=\"{$bgColor} {$fontColor}\"";
            $class     = empty($fontColor) ? $fgColor : ''; 
        } else {
            $fontColor = strpos($fgColor, 'color:') === false  ? $fgColor : '';
            $style = empty($fontColor) ? $fgColor : '';
            $class = "{$bgColor} {$fontColor}\"";
        }
        return array('style' => $style, 'class' => $class);
    }
    
    public static function setStyle($style) 
    {
        return "style=\"{$style}\"";
    }
    
    public static function genImgArray(array $aImages, int $linesCount): array 
    {
        $imgCount   = count($aImages);
        $rest       = $imgCount % $linesCount;
        $imgPerLine = $rest > 0 ? floor($imgCount/$linesCount) : ($imgCount/$linesCount) - 1;
        
        $cLines     = 0;
        $cImages    = 0;
        
        foreach($aImages as $img) {
            $pics[$cLines][$cImages]  = $img['photo'];
            $frame[$cLines][$cImages] = $img['frame'];
            $id[$cLines][$cImages]    = $img['id'];
            $title[$cLines][$cImages] = $img['title'];
            $des[$cLines][$cImages]   = $img['description'];
            $total[$cLines][$cImages] = isset($img['total']) ? $img['total'] : '';
            $soft[$cLines][$cImages]  = isset($img['soft']) ? $img['soft'] : '';
            $all[$cLines][$cImages]   = isset($img['all']) ? $img['all'] : '';
            if ($cImages < $imgPerLine) {
                $cImages += 1;
            } else {
                $cImages = 0;
                $cLines += 1;
            }  
        }
        return array(
            'pics' => $pics,
            'frame' => $frame,
            'id' => $id,
            'title' => $title,
            'desc' => $des,
            'total' => $total,
            'soft' => $soft,
            'all' => $all   
        );
    }
    
    public static function drawGDImage($imageName, $image, $imageClass): string
    {
        $content  = "<div id=\"img_{$imageName}\" class=\"img-container {$imageClass}\">\n";
        $content .= "   <img src='data:image/jpeg;base64," . $image ."'>\n";
        $content .= "</div>/n";
        return $content;
    }
    
    public static function drawCarousel($carouselName, $carouselClass, $carouselContent, $carouselAttributes)
    {
        $carouselClass = !empty($carouselClass) ? "class=\"{$carouselClass}\"" : '';
        return "<div data-role=\"carousel\" id=\"carousel_{$carouselName}\" {$carouselClass} data-cls-controls=\"fg-black\" data-control-next=\"<span class='mif-chevron-right'></span>\" data-control-prev=\"<span class='mif-chevron-left'></span>\" {$carouselAttributes}>{$carouselContent}</div>\n";
    }
    
    public static function drawSlider(){
        
    }
    
    public static function genCarouselOrSilderContent(array $aImages, string $path, bool $link): string
    {
        $out = '';
        $it = 0;
        foreach ($aImages as $img) {
            $cols   = $img['frame'] === 'landscape' ? 10 : 5;
            $image  = self::drawImage($img['photo'], GALLERY_PATH, '', '');
            $holder = $link ? self::drawLink($img['photo'],"", '', $image, '') : $image;
            $cell = self::drawCell('', '', $holder, '', $cols);
            $row  = self::drawRow('', 'd-flex flex-justify-center', $cell, '');
            $out .= "<div class=\"slide\" onmousemove=\"setImageData('{$img['photo']}', {$it});\">{$row}</div>\n";
            $it++;
        }
        return $out;    
    }
    
    // ************************************* TILES ****************************
    
    public static function drawTile($tileName, $tileClass, $tileIcon, $tileCaption, $tileBadge)
    {
        $tileName = $tileName !== '' ? "id=\"{$tileName}\"" : '';
        $tileClass = $tileClass !== '' ? "class=\"{$tileClass}\"" : '';
        $content  = "<div {$tileName} data-role=\"tile\" {$tileClass} >\n";
        $content .= $tileIcon    !== '' ? "    <span class=\"{$tileIcon}\"></span>\n"              : '';
        $content .= $tileCaption !== '' ? "    <span class=\"branding-bar\">{$tileCaption}</span>\n"   : '';
        $content .= $tileBadge   !== '' ? "    <span class=\"badge-top\">{$tileBadge}</span>\n" : '';
        $content .= "</div>\n";
        return $content;
    }
    
    public static function drawLinkedTile($tileName, $tileLink, $tileClass, $tileIcon, $tileCaption, $tileBadge, $tileSize): string
    {
        $content  = "<a href=\"". HOME ."/{$tileLink}\" id=\"{$tileName}\" data-role=\"tile\" data-size=\"{$tileSize}\" class=\"{$tileClass}\">\n";
        $content .= $tileIcon    !== '' ? "    <span class=\"{$tileIcon}\"></span>\n"              : '';
        $content .= $tileCaption !== '' ? "    <span class=\"branding-bar\">{$tileCaption}</span>\n"   : '';
        $content .= $tileBadge   !== '' ? "    <span class=\"badge-top\">{$tileBadge}</span>\n" : '';
        $content .= "</a>\n";
        return $content;
    }
    
    public static function drawTileGroupTitle($tileGroupTitle, $tileGroupContent): string
    {
        $content = "<div class=\"tiles-grid tiles-group size-2\" data-group-title=\"{$tileGroupTitle}\">\n";
        $content .= $tileGroupContent;
        $content .= "</div>\n";
        return $content;
    }
    
    public static function isJson($jsonString)
    {
        json_decode($jsonString);
        return json_last_error() === 0 ? true : false;
    }
    
    //************************************** LISTS *******************************************
    
    public static function drawArrayToList($listName, $listClass, $listData, $listAttributes)
    {
        $listName  = !empty($listName)  ? "id=\"{$listName}\"" : '';
        $listClass = !empty($listClass) ? $listClass : "items-list";
        $content   = "<div {$listName}  class=\"{$listClass}\" {$listAttributes}>";
        foreach ($listData as $l) {
            $content .= '   <div class="item">';
            $content .= '       <span class="label">' . utf8_decode($l) .'</span>';
            $content .= '   </div>';
        }
        $content .= '</div>';
        return $content;
    }
    
    //************************************** CHARM *******************************************
    
    public static function drawCharm($charmName, $charmClass, $charmContent, $charmAttributes)
    {
        $content = "<div id=\"charm_{$charmName}\" {$charmClass} data-role=\"charms\" {$charmAttributes} >$charmContent</div>";
        return $content;
    }
    
    //************************************** VIDEO PLAYER *******************************************
    
    public static function drawVideoPlayer($videoName, $videoSource, $videoLogoSrc, $videoLogoHeight, $videoLink, $videoHideControls)
    {
        $name     = !empty($videoName) ? "id=\"{$videoName}\"" : "";
        $content  = "<video {$name} data-role=\"video\"";
        $content .= !empty($videoSource)       ? "data-src=\"{$videoSource}\""                 : "";
        $content .= !empty($videoLogoSrc)      ? "data-logo=\"{$videoLogoSrc}\""               : "";
        $content .= !empty($videoLogoHeight)   ? "data-logo-height=\"{$videoLogoHeight}\""     : "";
        $content .= !empty($videoLink)         ? "data-logo-target=\"$videoLink\""             : "";
        $content .= !empty($videoHideControls) ? "data-controls-hide=\"{$videoHideControls}\"" :  "data-controls-hide=\"3000\"";
        $content .= "></video>";
        return $content;
    }
    
    //************************************** AUDIO PLAYER *******************************************
    
    public static function drawAudioPlayer($audioName, $audioSource, $audioVolume, $audioAutoPlay = false, $audioColor = 'def')
    {
        $audioVolume = empty($audioVolume) ? "5" : $audioVolume;
        $audioName   = empty($audioName)   ? "id=\"{$audioName}\"" : '';
        $volume      = $audioVolume === 10 ? "1" : ".{$audioVolume}";
        $content     = "<audio {$audioName} data-role=\"audio\"";
        $content    .= !empty($audioSource)    ? "data-src=\"{$audioSource}\"" : "";
        $content    .= $audioColor === 'def'   ? "" : "class=\"light\"";
        $content    .= $audioAutoPlay === true ? "data-autoplay=\"true\"" : "";
        $content    .= "data-volume=\"{$volume}\"";
        $content    .= "></audio>\n";
        return $content;
    }
    
    //************************************** TAG INPUT *******************************************
    
    public static function drawTagInput($tagInputName, $tagInputClass, $tagInputValue, $tagInputAttributes)
    {
        $tagInputName       = !empty($tagInputName)       ? "id=\"{$tagInputName}\" name=\"{$tagInputName}\"" : "";
        $tagInputClass      = !empty($tagInputClass)      ? "class=\"{$tagInputClass}\""                      : "";
        $tagInputValue      = !empty($tagInputValue)      ? "value=\"{$tagInputValue}\""                      : "";
        $tagInputAttributes = !empty($tagInputAttributes) ? $tagInputAttributes                               : "";
        return "<input type=\"text\" {$tagInputName} {$tagInputClass} data-role=\"taginput\" {$tagInputValue} {$tagInputAttributes}>\n";
    }
    
    //************************************** WINDOWS LIKE DIV *********************************************
    
    public static function drawWindowsDiv($windowName, $windowIcon, $windowTitle, $windowContent, $minimize = true, $maximize = true, $close = true)
    {
        $content  = "<div id=\"{$windowName}\" class=\"window\">";
        $content .= "   <div class=\"window-caption\">";
        $content .= !empty($windowIcon) ? "       <span class=\"icon {$windowIcon}\"></span>" : "";
        $content .= "       <span class=\"title\">{$windowTitle}</span>";
        $content .= "       <div class=\"buttons\">";
        $content .= $minimize ? "           <span class=\"btn-min\"></span>" : "";
        $content .= $maximize ? "           <span class=\"btn-max\"></span>" : "";
        $content .= $close ? "           <span class=\"btn-close\"></span>" : "";
        $content .= "       </div>";
        $content .= "   </div>";
        $content .= "   <div class=\"window-content p-2\">";
        $content .= $windowContent;
        $content .= "   </div>";
        $content .= "</div>";
        return $content;
    }
    
    /******************************************************************************************************
    *         
    *           FUNCIONES DE OBJETOS DE FORMULARIO
    *  
    *******************************************************************************************************/
        
    //************************************** FORM **********************************************************
        
    public static function drawForm($formName, $formMethod, $formAction, $formContent, $formAttributes, $isJavaAction = true): string 
    {
        $action = $isJavaAction ? "javascript:" . $formAction : $formAction;
        return "<form id=\"{$formName}\" name=\"{$formName}\" method=\"{$formMethod}\" action=\"{$action}\" {$formAttributes}>\n{$formContent}</form>\n";
    }
    
    //************************************** DATEPICKER ****************************************************
    
    public static function drawDatePicker($datePickeName, $datePickerClass, $datePickerAttributes)
    {
        return "<input data-role=\"datepicker\" id=\"{$datePickeName}\" name=\"{$datePickeName}\" class=\"$datePickerClass\" {$datePickerAttributes}>\n";
    }
    
    public static function drawPicker($datePickeName, $datePickerClass, $datePickerAttributes)
    {
        return "<input data-role=\"calendarpicker\" id=\"{$datePickeName}\" name=\"{$datePickeName}\" class=\"$datePickerClass\" {$datePickerAttributes}>\n";
    }
    
    //************************************** SELECT ********************************************************
    
    public static function drawArrayToSelect($selectName, $selectClass, $selectOptions = array(), $selectAttributes, $emptyOption, $firstLetter = true, $selected = '')
    {
        if (!is_array($selectOptions) || count($selectOptions) == 0) {
            return '';
        }
        $selectAttributes = $selectAttributes !== '' ? 'data-role="select" ' . $selectAttributes : 'data-role="select"';
        $content  = "<select id=\"{$selectName}\" name=\"{$selectName}\" class=\"{$selectClass}\" {$selectAttributes}>"; 
        $content .= $emptyOption ? "<option value=\"NULL\">(ninguno)</option>" : '';
        foreach ($selectOptions as $opt) {
            switch (gettype($opt)) {
                case 'string':
                    $sel  = $selected === $opt ? "selected=\"selected\"" : "";
                    $text = $firstLetter === true ? ucfirst(utf8_decode($opt)) : utf8_decode($opt);
                    $content .= "    <option value=\"" . utf8_decode($opt) . "\" {$sel} >" .$text  . "</option>";
                    break;
                case 'array':
                    if (isset($opt) && (!empty($opt['DisplayText']) || !empty($opt['Value']))) {
                        $sel  = $selected === $opt['Value'] || $selected === $opt['DisplayText'] ? "selected=\"selected\"" : "";
                        $attr = isset($opt['attr']) ? 'attr="' . $opt['attr'] . '"' : '';
                        if (empty($opt['DisplayText']) && !empty($opt['Value'])) {
                            $text = $firstLetter === true ? ucfirst(utf8_decode($opt['Value'])) : utf8_decode($opt['Value']);
                            $content .= "    <option value=\"" . utf8_decode($opt['Value']) . "\" {$sel} {$attr}>" . $text . "</option>";
                        } elseif(!empty($opt['DisplayText']) && empty($opt['Value'])) {
                            $text = $firstLetter === true ? ucfirst(utf8_decode($opt['DisplayText'])) : utf8_decode($opt['DisplayText']);
                            $content .= "    <option value=\"" . utf8_decode($opt['DisplayText']) . "\" {$sel} {$attr}>" . $text . "</option>";
                        } else {
                            $text = $firstLetter === true ? ucfirst(utf8_decode($opt['DisplayText'])) : utf8_decode($opt['DisplayText']);
                            $content .= "    <option value=\"" . utf8_decode($opt['Value']) . "\" {$sel} {$attr}>" . $text . "</option>";
                        }  
                    } 
                    break;
            }
        }
        $content .= "</select>";
        return $content;
    }
    
    public static function drawArrayToSelectWithGroups(
        $selectName, 
        $selectClass, 
        $selectOptions = array(), 
        $selectAttributes, 
        $emptyOption, 
        $firstLetter = true, 
        $selected = ''
    ) {
        if (!is_array($selectOptions) || count($selectOptions) == 0) {
            return '';
        }
        $selectAttributes = !empty($selectAttributes) ? 'data-role="select" ' . $selectAttributes : 'data-role="select"';
        $content  = "<select id=\"{$selectName}\" name=\"{$selectName}\" class=\"{$selectClass}\" {$selectAttributes}>"; 
        $content .= $emptyOption === true ? "<option value=\"NULL\">(ninguno)</option>" : '';
        foreach ($selectOptions as $opt) {
            $name = key($opt);
            $content .= "<optgroup label=\"{$name}\">";
            foreach ($opt[$name] as $key => $val) {
                $content .= "    <option value=\"" . $val . "\">" . $key . "</option>";
            }
            $content .= "</optgroup>";
        }
        $content .= "</select>";
        return $content;
    }
    
    //************************************** TEXT **********************************************************
    
    public static function drawTextField($textName, $textClass, $textValue, $textAttributes)
    {
        $textName       = !empty($textName) ? "id=\"{$textName}\" name=\"{$textName}\"" : '';
        $textAttributes = !empty($textAttributes) ?  Controls::defaultTextAttributes() . $textAttributes : Controls::defaultTextAttributes();
        return "<input type=\"text\" {$textName} class=\"{$textClass}\" value=\"{$textValue}\" {$textAttributes}>\n";
    }
    
    public static function defaultTextAttributes()
    {
        return "data-role=\"input\" ";
    }
    
    //************************************** HIDDEN *********************************************************
    
    public static function drawHidden($textName, $textValue)
    {
        $textName = !empty($textName) ? "id=\"{$textName}\" name=\"{$textName}\"" : '';
        return "<input type=\"hidden\" {$textName} value=\"{$textValue}\">\n";
    }
    
    //************************************** TEXTAAREA ******************************************************
    
    public static function drawTextArea($areaName, $areaContent, $areaAttributes, $areaClass = '')
    {
        $areaName = !empty($areaName) ? "id=\"{$areaName}\" name=\"{$areaName}\"" : '';
        $areaAttributes = $areaAttributes != '' ? " data-role=\"textarea\" " . $areaAttributes : "data-role=\"textarea\"";
        return "<textarea {$areaClass} {$areaName} {$areaAttributes}>{$areaContent}</textarea>\n";
    }
    
    //************************************** CHECKBOX *******************************************************
    
    public static function drawCheckBox($checkName, $checkClass, $checkAttributes)
    {
        $checkName = !empty($checkName) ? "id=\"{$checkName}\" name=\"{$checkName}\"" : '';
        $checkAttributes = $checkAttributes !== '' ? "data-role=\"checkbox\" " . $checkAttributes : "data-role=\"checkbox\""; // data-role=\"switch\"
        return "<input type=\"checkbox\" {$checkName} {$checkAttributes}>";
    }
    
    public static function drawMultipleCheckBoxes($checkName, $checkClass, $checkOptions = Array(), $checkAttributes)
    {
        $content =  "<script>
                        
                     </script>
                    ";
        for($a = 0; $a < count($checkOptions); $a++){
            $content .= Controls::drawCheckBox($checkName . "_{$a}", $checkClass, "data-role=\"checkbox\" data-caption=\"" . $checkOptions[$a] . "\"");
        }
        echo $content;
    }
    
    //************************************** RADIOBUTTON ****************************************************
   
    public static function drawRadioButton($radioId, $radioName, $radioValue, $radioClass, $radioAttributes)
    {
        $radioId = !empty($radioId) ? "id=\"{$radioId}\"" : ''; 
        $radioName = !empty($radioName) ? "name=\"{$radioName}\"" : '';
        $radioAttributes = !empty($radioAttributes) ? $radioAttributes : "data-role=\"checkbox\"";
        return "<input type=\"radio\" {$radioId} {$radioName} value=\"" . utf8_decode($radioValue) . "\" {$radioAttributes}>";
    }
    
    public static function drawMultipleRadioButtons($radioName, $radioClass, $radioOptions = Array(), $radioAttributes, $vertical)
    {
        $content =  "";
        for ($a = 0; $a < count($radioOptions); $a++) {
            $content .= Controls::drawRadioButton(
                "{$radioName}_{$a}", 
                "{$radioName}", 
                $radioOptions[$a]['name'], 
                $radioClass, 
                "data-role=\"radio\" data-style=\"2\" data-caption=\"" . 
                    utf8_decode($radioOptions[$a]['text']) . "\"" . $radioAttributes 
            );
            $content .= $vertical === true ? "<br />" : '';
        }
        return $content;
    }
    
    //************************************** SWITCHBOX ******************************************************
   
    public static function drawSwitchBox($switchId, $switchName, $switchValue, $switchClass, $switchAttributes)
    {
        $switchId   = !empty($switchId) ? "id=\"{$switchId}\"" : ''; 
        $switchName = $switchName !== '' ? "name=\"{$switchName}\"" : '';
        $switchAttributes = $switchAttributes !== '' ? $switchAttributes : "data-role=\"switch\"";  
        return "<input type=\"radio\" {$switchClass} {$switchId} {$switchName} value=\"" . utf8_decode($switchValue) . "\" {$switchAttributes}>";
    }
    
    public static function drawMultipleSwitchBox($switchName, $switchClass, $switchOptions = Array(), $switchAttributes, $vertical)
    {
        $content =  "";
        for ($a = 0; $a < count($switchOptions); $a++) {
            $content .= Controls::drawRadioButton(
                "{$switchName}_{$a}", 
                "{$switchName}", 
                $switchOptions[$a]['name'], 
                $switchClass, 
                "data-role=\"radio\" data-style=\"2\" data-caption=\"" . 
                    utf8_decode($switchOptions[$a]['text']) . "\"" . $switchAttributes 
            );
            $content .= $vertical === true ? "<br />" : '';
        }
        return $content;
    }
    
    //************************************** FILES UPLOAD ***************************************************
    
    public static function drawFileUpload($fileUplName, $fileUplClass, $fileUplAttributes)
    {
        $fileUplClass = !empty($fileUplClass) ? "class=\"{$fileUplClass}\"" : '';
        $fileUplAttributes = !empty($fileUplAttributes) ? $fileUplAttributes : '';
        $content = "<input id=\"{$fileUplName}\" name=\"{$fileUplName}\" {$fileUplClass} type=\"file\" data-role=\"file\" {$fileUplAttributes}>";
        return $content;
    }
    
    public static function drawFileDrop($dropName, $dropClass, $dropAttributes)
    {
        $dropClass = !empty($dropClass) ? "class=\"{$dropClass}\"" : '';
        $dropAttributes = !empty($dropAttributes) ? $dropAttributes : '';
        $content = "<input id=\"{$dropName}\"  type=\"file\" data-role=\"file\" data-mode=\"drop\" >\n";
        return $content;
    }
    
    public static function drawDirectoryUpload()
    {
        return "<input type=\"file\" name=\"files[]\" id=\"files\" multiple=\"\" directory=\"\" webkitdirectory=\"\" mozdirectory=\"\">";
    }
    
    //************************************** BUTTONS *******************************************
    
    public static function drawButton($buttonName, $buttonClass, $buttontText, $buttonAttributes)
    {
        $buttonName  = $buttonName !== '' ? "id=\"{$buttonName}\" name=\"{$buttonName}\"" : '';
        $buttonClass = $buttonClass !== '' ? "class=\"button  {$buttonClass}\"" : 'class="button"';
        return "<button {$buttonName} {$buttonClass} {$buttonAttributes}>{$buttontText}</button>";
    }
    
    public static function drawImageButton($buttonName, $buttonClass, $buttontText, $buttonAttributes)
    {
        $buttonName  = $buttonName !== '' ? "id=\"{$buttonName}\" name=\"{$buttonName}\"" : '';
        $buttonClass = $buttonClass !== '' ? "class=\"image-button  {$buttonClass}\"" : 'class="button"';
        return "<button {$buttonName} {$buttonClass} {$buttonAttributes}>{$buttontText}</button>";
    }
    
    public static function drawSubmitButton($buttonName, $buttonClass, $buttontText, $buttonAttributes)
    {
        $buttonName = !empty($buttonName) ? "id=\"{$buttonName}\" name=\"{$buttonName}\"" : '';
        $buttonAttributes = !empty($buttonAttributes) ? $buttonAttributes : "";
        return "<input type=\"submit\" {$buttonName} class=\"button {$buttonClass}\" value=\"{$buttontText}\" {$buttonAttributes}>";
    }

    //************************************** Panel *********************************************************
    
    public static function drawPanel($panelName, $panelClass, $panelAttributes, $panelContent)
    {
        $name = $panelName !== '' ? "id=\"{$panelName}\" name=\"{$panelName}\"" : '';
        return "<div {$name} data-role=\"panel\" {$panelAttributes} {$panelClass} >\n{$panelContent}</div>\n";
    }
}
