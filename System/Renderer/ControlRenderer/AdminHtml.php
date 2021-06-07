<?php

namespace System\Renderer\ControlRenderer;

use System\Renderer\ControlRenderer\ControlInterface as ControlInterface;

class AdminHtml extends AbstractControl implements ControlInterface
{
    public function __construct($typeData, $data)
    {
        parent::__construct($typeData, $data);
        $this->setData();
    }
    
    public function setData()
    {
        $this->content = '';
        foreach ($this->typeData as $key => $data) {
            if (!property_exists(__CLASS__, $key)) {
                continue;
            }
            $this->$key = $data;
        }
    }
    
    public function render(): string
    {
        $content  = "    <script defer>\n";
        $content .= $this->javascriptSetGlobalValues();
        $content .= $this->javascriptTableFunctions();
        $content .= $this->javascriptGridFunctionCall();
        $content .= "    </script>\n";
        $content .= "        <div id=\"section_admin_grid\" class=\"grid\">\n";
        $content .= "            <div id=\"row_admin_grid\" class=\"row\">\n";
        $content .= "                <div class=\"cell-12 title_background mt-5 ml-5 pl-5 p-3 fg-white text-leader\">\n";
        $content .= "                    <strong>{$this->data['titulo']}</strong>\n";
        $content .= "                </div>\n";
        $content .= "                <div id=\"maingrid\" class=\"cell-12 p-5\"></div>\n";
        $content .= "            </div>\n";
        $content .= "        </div>\n";
        $content .= "        <div id=\"hiddenData\"></div>\n";
        $content .= "        <div id=\"modals\">\n";
        $content .= "            <div id=\"translateModal\" class=\"Modal\" style=\"z-index: 1010;\">\n";
        $content .= "                <div id=\"translateContent\" class=\"Modal-content\" style=\"\">\n";
        $content .= "                     <div class=\"bg-darkBlue fg-white\" style=\"height: 45px;font-size: 20px; font-weight: bold\">\n";
        $content .= "                         <div id=\"translateModalTitle\" class=\"px-3 py-2 mb-3\" style=\"float: left;width: 90%\">\n";
        $content .= "                             Traducir:\n";
        $content .= "                         </div>\n";
        $content .= "                         <div class=\"px-3\" style=\"float: left;width: 10%;\">\n";
        $content .= "                             <span id=\"translateClose\" class=\"ModalClose\" onclick=\"closeModal('translate')\">&times;</span><p>&nbsp;</p>\n";
        $content .= "                         </div>\n";
        $content .= "                     </div>\n";
        $content .= "                     <div id=\"translateModalContent\" class=\"py-3\" style=\"clear: both;overflow-x: auto;white-space: nowrap;\"></div>\n";
        $content .= "                </div>\n";
        $content .= "            </div>\n";
        $content .= "       </div>\n";
        return $content;
    }
    
    private function javascriptSetGlobalValues()
    {
        $content  = "    homePath = '" . HOME . "';\n";
        $content .= "    fatherName = '{$this->data['nombre']}';\n";
        $content .= "    langSwitch = '"  . LANG_SWITCH ."';\n";
        $content .= "    creationType = '" . DEFAULT_GRID_DRAW_METHOD ."';\n\n";
        return $content;
    }
    
    private function javascriptTableFunctions() 
    {
        $content  = "    var entities_table = $('#tbl_{$this->data['nombre']}');\n";
        $content .= "    var table = entities_table.data(\"table\");\n\n";    
        $content .= "    var tableFuncs = {\n";
        $content .= "        onDrawCell: function (td, raw_data, index, head) {\n";
        $content .= "            var html = '';\n";
        $content .= "            if (head.name === \"is_active\") {\n";
        $content .= "                html = parseInt(raw_data) === 1 ? \"<span class='fg-green mif-checkmark'></span>\" : \"<span class='fg-red mif-minus js-archive-record'></span>\";\n";
        $content .= "                td.html(html);\n";
        $content .= "            }\n";
        $content .= "            if (head.name === \"is_editor\") {\n";
        $content .= "                html = parseInt(raw_data) === 1 ? \"<span class='fg-green mif-checkmark'></span>\" : \"<span class='fg-red mif-minus'></span>\";\n";
        $content .= "                td.html(html);\n";
        $content .= "            }\n";
        $content .= "            if (head.name === \"is_scanner\") {\n";
        $content .= "                html = parseInt(raw_data) === 1 ? \"<span class='fg-green mif-checkmark'></span>\" : \"<span class='fg-red mif-minus'></span>\";\n";
        $content .= "                td.html(html);\n";
        $content .= "            }\n";
        $content .= "        },\n\n";
        $content .= "        onDrawRow: function (tr) {\n";
        $content .= "            if (tr.find(\".js-archive-record\").length > 0) {\n";
        $content .= "                tr.addClass(\"archive-record bg-lightBlue\");\n";
        $content .= "            }\n";
        $content .= "        },\n\n";
        $content .= "        archiveFilterIndex: 0,\n";
        $content .= "        addArchiveFilter: function () {\n";
        $content .= "            if (entities_table.length === 0) {\n";
        $content .= "                return;\n";
        $content .= "            }\n";
        $content .= "            this.archiveFilterIndex = table.addFilter(function (row, heads) {\n";
        $content .= "                var is_active_index = 0;\n";
        $content .= "                heads.forEach(function (el, i) {\n";
        $content .= "                    if (el.name === \"is_active\") {\n";
        $content .= "                        is_active_index = i;\n";
        $content .= "                    }\n";
        $content .= "                });\n";
        $content .= "                return parseInt(row[is_active_index]) === 1;\n";
        $content .= "            }, true);\n";
        $content .= "        },\n\n";
        $content .= "        removeArchiveFilter: function () {\n";
        $content .= "            if (entities_table.length === 0) {\n";
        $content .= "                return;\n";
        $content .= "            }\n";
        $content .= "            table.removeFilter(this.archiveFilterIndex, true);\n";
        $content .= "        }\n\n";
        $content .= "    };\n\n";
        return $content;
    }
    
    private function javascriptGridFunctionCall()
    {
        $content  = "    $(window).on('load', function(){\n";
        $content .= "        searchColors('colors', 'hiddenData');\n\n";
        $content .= "        createHiddenData('{$this->data['nombre']}_color', 'hiddenData', {'name': 'darkBlue'});\n\n";
        $content .= "        createGridAndGridSpecialFields(\n";
        $content .= "            '{$this->data['nombre']}',\n";
        $content .= "            '{$this->data['nombre']}',\n";
        $content .= "            '',\n";
        $content .= "            'maingrid'\n";
        $content .= "        );\n";
        $content .= "    });\n\n";
        return $content;
    }
    
}
