<?php

namespace System\Core;

class Chart {


    /******************************************************************************************************
    *         
    *           FUNCIONES DE DIBUJADO DE GRAFICAS
    *  
    *******************************************************************************************************/


    /******************************************************************************************************
    *         
    *           PIE CHART - GRAFICO DE PIZZA
    *  
    *******************************************************************************************************/

    public static function genPieChart($type, $title, $getData, $data, $q)
    {
        switch ($type) {
            case 'pie':
            case 'donut':
                $startAngle = 0;
                $endAngle = 360;
                break;
            case 'halfPie':
            case 'halfDonut':
                $startAngle = -90;
                $endAngle = 90;
                break;
        }
        $chart = array();

        $chart['countTotal'] = $q;
        //general
        $chart['credits'] = null;
        $chart['credits']['enabled'] = null;
        $chart['chart']['plotBackgroundColor'] = null;
        $chart['chart']['plotBorderWidth'] = 0;
        $chart['chart']['plotShadow'] = false;
        $chart['chart']['style']['fontFamily'] = 'Helvetica Neue, sans-serif';

        //title
        $chart['title']['height'] = '';
        $chart['title']['text'] = $title;
        $chart['title']['style']['display'] = 'none';
        $chart['title']['plotShadow'] = false;
        $chart['title']['align'] = 'left';
        $chart['title']['verticalAlign'] = 'top';
        $chart['title']['y'] = 12; 
        $chart['title']['style']['color'] = '#FD341F';
        $chart['title']['style']['fontSize'] = '40px';
        $chart['title']['style']['fontWeight'] = 'bold';

        //subtitle
        $chart['subtitle']['height'] = '';
        $chart['subtitle']['text'] = number_format($data, 2, ',', '.') . '%';
        $chart['subtitle']['plotShadow'] = false;
        $chart['subtitle']['align'] = 'center';
        $chart['subtitle']['verticalAlign'] = 'middle';
        if ($type === 'pie' || $type === 'halfPie') {  
            $chart['subtitle']['align'] = 'center';
            $chart['subtitle']['verticalAlign'] = 'top';
            $chart['subtitle']['y'] = 0; 
            $chart['subtitle']['x'] = 0;
        } else {
            $chart['subtitle']['align'] = 'center';
            $chart['subtitle']['verticalAlign'] = 'middle';
            $chart['subtitle']['x'] = 0;
            $chart['subtitle']['x'] = 0;
        }
        if($type === 'donut')       $chart['subtitle']['y'] = 0;
        if($type === 'halfDonut')   $chart['subtitle']['y'] = 90;
        $chart['subtitle']['style']['color'] = '#00A0C6';
        $chart['subtitle']['style']['fontSize'] = '55px';
        $chart['subtitle']['style']['fontWeight'] = 'bold';

        $chart['legend']['enabled'] = false;
        $chart['legend']['align'] = 'center';
        $chart['legend']['verticalAlign'] = 'top';
        $chart['legend']['layout'] = 'horizontal';
        $chart['legend']['x'] = -60;

        $chart['tooltip']['pointFormat'] = '{series.name}: <b>{point.y:.1f}% [{point.count}]</b>';

        //dataLabels
        $chart['plotOptions']['pie']['dataLabels']['enabled'] = true;
        $chart['plotOptions']['pie']['dataLabels']['format'] = '{point.count}';
        $chart['plotOptions']['pie']['dataLabels']['distance'] = -50;
        $chart['plotOptions']['pie']['dataLabels']['style']['fontWeight'] = 'bold';
        $chart['plotOptions']['pie']['dataLabels']['style']['color'] = 'black';
        $chart['plotOptions']['pie']['showInLegend'] = true; 

        //chart definition
        $chart['plotOptions']['pie']['startAngle'] = $startAngle;
        $chart['plotOptions']['pie']['endAngle'] = $endAngle;
        $chart['plotOptions']['pie']['center'] = $type === 'halfDonut' || $type === 'halfPie' ? ['50%', '100%'] : '';
        $chart['plotOptions']['pie']['size']   = $type === 'halfDonut' || $type === 'halfPie' ? '200%' : '100%';

        //series
        $chart['series'][0]['type'] = 'pie';
        $chart['series'][0]['name'] = 'Porcentaje';
        $chart['series'][0]['innerSize'] = $type === 'halfDonut' || $type === 'donut' ? '60%' : '';
        $chart['series'][0]['data'] = $getData; 
        $json = json_encode($chart, JSON_NUMERIC_CHECK);
        echo str_replace(['"<script>','<script>"'], '',$json);
    }



    /******************************************************************************************************
    *         
    *           BAR CHART - GRAFICO de BARRAS
    *  
    *******************************************************************************************************/


    public static function genBarChart($title, $getData, $count, $searchType, $barColor)
    {
        $chart = array(); 

        // Agregar el total de casos al array
        $chart['countTotal'] = $count;
        //general
        $chart['credits'] = null;
        $chart['credits']['enabled'] = null;
        $chart['exporting']['enabled'] = true; 
        $chart['chart']['type'] = 'bar';
        //$chart['chart']['y'] = 30;
        $chart['chart']['plotBackgroundColor'] = null;
        $chart['chart']['plotBorderWidth'] = 0;
        $chart['chart']['plotShadow'] = false;
        //$chart['chart']['margin'] = -20;
        $chart['chart']['style']['fontFamily'] = 'Helvetica Neue, sans-serif';

        //title
        $chart['title']['margin'] = 50;
        $chart['title']['floating'] = false;
        $chart['title']['height'] = 40;
        $chart['title']['text'] = $title;
        $chart['title']['style']['display'] = 'none';
        $chart['title']['plotShadow'] = false;
        $chart['title']['align'] = 'left';
        $chart['title']['verticalAlign'] = 'top';
        $chart['title']['y'] = 20; 

        $chart['title']['style']['color'] = '#00A0C6';
        $chart['title']['style']['fontSize'] = '20px';
        $chart['title']['style']['fontWeight'] = 'bold';

        //EJE X
        if (is_array($getData)) {
            $chart['xAxis'][0]['categories'] = array_column($getData, 'name');
        }
        $chart['xAxis'][0]['type'] = 'category';
        $chart['xAxis'][0]['title']['text'] = '';
        //EJE X OPUESTO
        $chart['xAxis'][1]['opposite']      = true;
        if(is_array($getData)){
            $chart['xAxis'][1]['categories'] = array_column($getData, 'opposite');
        }
        $chart['xAxis'][1]['linkedTo']      = 0;
        $chart['xAxis'][1]['type']          = 'category';
        $chart['xAxis'][1]['title']['text'] = null;

        // EJE Y
        $chart['yAxis'][0]['lineWidth']     = 1;
        $chart['yAxis'][0]['title']['text'] = null;
        // EJE Y OPUESTO
        $chart['yAxis'][1]['lineWidth']     = 1;
        $chart['yAxis'][1]['linkedTo']      = 0;
        $chart['yAxis'][1]['opposite']      = true;
        $chart['yAxis'][1]['title']['text'] = null;

        //LEGEND
        $chart['legend']['enabled']         = false;

        if ($searchType === 'nps' || $searchType === 'porcentaje') {
            $chart['tooltip'] = Chart::genPointFormat($searchType);
        } else {
            $chart['tooltip'] = Chart::genPointFormat($searchType);
        }

        //dataLabels
        $chart['plotOptions']['series']['stacking'] = 'normal';
        $chart['plotOptions']['series']['pointWidth'] = 20;
        $chart['plotOptions']['series']['pointPadding'] = 20;
        $chart['plotOptions']['series']['dataLabels']['enabled'] = true;
        $chart['plotOptions']['series']['dataLabels']['allowOverlap'] = false;
        //$chart['plotOptions']['series']['dataLabels']['distance'] = -50;
        $chart['plotOptions']['series']['dataLabels']['style']['fontWeight'] = 'bold';
        $chart['plotOptions']['series']['dataLabels']['style']['color'] = 'black';
        $chart['plotOptions']['series']['showInLegend'] = true;

        //series
        $chart['series'][0]['type']   = 'bar';
        $chart['series'][0]['color']  = '#' . Chart::setBarColor($barColor);
        $chart['series'][0]['name']   = Chart::genPointName($searchType);
        $chart['series'][0]['data']   = $getData;
        $json = json_encode($chart, JSON_NUMERIC_CHECK);
        echo str_replace(['")','("'], ['',''],$json);
    }

    /******************************************************************************************************
    *         
    *           STACKEDBAR CHART - GRAFICO DE BARRAS APILADAS
    *  
    *******************************************************************************************************/

    public static function genStackedBarChart($title, $getData, $count, $categories)
    {
        $chart = array(); 
        // Agregar el total de casos al array
        $chart['countTotal'] = $count;
        // General
        $chart['credits']                       = null;
        $chart['credits']['enabled']            = null;
        $chart['exporting']['enabled']          = true; 
        $chart['chart']['type']                 = 'bar';
        // $chart['chart']['y'] = 30;
        $chart['chart']['plotBackgroundColor']  = null;
        $chart['chart']['plotBorderWidth']      = 0;
        $chart['chart']['plotShadow']           = false;
        // $chart['chart']['margin'] = -20;
        $chart['chart']['style']['fontFamily']  = 'Helvetica Neue, sans-serif';

        // Title
        $chart['title']['margin']               = 50;
        $chart['title']['floating']             = false;
        $chart['title']['height']               = 40;
        $chart['title']['text']                 = $title;
        $chart['title']['style']['display']     = 'none';
        $chart['title']['plotShadow']           = false;
        $chart['title']['align']                = 'left';
        $chart['title']['verticalAlign']        = 'top';
        $chart['title']['y']                    = 20; 

        $chart['title']['style']['color']       = '#00A0C6';
        $chart['title']['style']['fontSize']    = '20px';
        $chart['title']['style']['fontWeight']  = 'bold';

        $chart['xAxis'][0]['categories']        = $categories === true ? array_pop($getData) : ['Porcentaje'];
        $chart['xAxis'][0]['type']              = 'category';
        $chart['xAxis'][0]['title']['text']     = '';

        /*
        //EJE X OPUESTO
        $chart['xAxis'][1]['opposite']      = true;
        if(is_array($getData)){
            $chart['xAxis'][1]['categories'] = array_column($getData, 'opposite');
        }
        $chart['xAxis'][1]['linkedTo']      = 0;
        $chart['xAxis'][1]['type'] = 'category';
        $chart['xAxis'][1]['title']['text'] = null;
        */

        // EJE Y
        $chart['yAxis'][0]['lineWidth']     = 1;
        $chart['yAxis'][0]['title']['text'] = null;
        // EJE Y OPUESTO
        $chart['yAxis'][1]['lineWidth']     = 1;
        $chart['yAxis'][1]['linkedTo']      = 0;
        $chart['yAxis'][1]['opposite']      = true;
        $chart['yAxis'][1]['title']['text'] = null;

        //LEGEND
        $chart['legend']['enabled']         = true;

        //dataLabels
        $chart['plotOptions']['column']['stacking']                          = 'percent';
        $chart['plotOptions']['column']['shared']                            = true;
        $chart['plotOptions']['series']['stacking']                          = true;
        $chart['plotOptions']['series']['pointWidth']                        = $categories === false ? 40 : 20;
        $chart['plotOptions']['series']['pointPadding']                      = 30;
        $chart['plotOptions']['series']['dataLabels']['enabled']             = true;
        $chart['plotOptions']['series']['dataLabels']['format']              = '{point.y:.2f}';
        $chart['plotOptions']['series']['dataLabels']['allowOverlap']        = false;
        $chart['plotOptions']['series']['dataLabels']['distance']            = -50;
        $chart['plotOptions']['series']['dataLabels']['style']['fontWeight'] = 'bold';
        $chart['plotOptions']['series']['dataLabels']['style']['color']      = 'black';
        $chart['plotOptions']['series']['showInLegend']                      = true;

        $chart['tooltip']['pointFormat'] = '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.percentage:.0f}%)<br/>';
        $chart['tooltip']['shared']      = false;
        //series
        /*
        $chart['series'][0]['type']   = 'bar';
        $chart['series'][0]['color']  = '#' . Chart::setBarColor($barColor);
        $chart['series'][0]['name']   = Chart::genPointName($searchType);
        $chart['series'][0]['data']   = $getData;
         * 
         */
        $chart['series'] = $getData;
        $json = json_encode($chart, JSON_NUMERIC_CHECK);
        echo str_replace(['")','("'], ['',''],$json);
    }

    /******************************************************************************************************
    *         
    *           HEATMAP CHART - GRAFICO DE MAPA DE CALOR
    *  
    *******************************************************************************************************/


    public static function genHeatMapChart($title, $getData)
    {
        $xCategories = $getData['xGroups'];
        $yCategories = $getData['yGroups'];
        $data        = $getData['data'];

        $chart = array(); 

        $chart['credits']['enabled']       = null;
        $chart['exporting']['enabled']     = true;

        $chart['chart']['type']            = 'heatmap';
        $chart['chart']['plotBorderWidth'] = 1;
        $chart['chart']['marginTop']       = 20;
        $chart['chart']['marginBottom']    = 130;    

        // Title
        $chart['title']['text']            = null;
        $chart['xAxis']['categories']      = $xCategories;
        $chart['yAxis']['categories']      = $yCategories;
        $chart['yAxis']['title']           = null;


        $chart['colorAxis']['min']      = 0;
        $chart['colorAxis']['minColor'] = '#FFFFFF';
        //$chart['colorAxis']['maxColor'] = '#003366';

        //LEGEND
        $chart['legend']['align']   = 'right';
        $chart['legend']['layout']  = 'vertical';
        $chart['legend']['margin']  = 0;
        $chart['legend']['verticalAlign'] = 'top';
        $chart['legend']['y'] = 25;
        $chart['legend']['symbolHeight'] = 280;

        $chart['series'][0]['name']        = 'Prueba';
        $chart['series'][0]['borderWidth'] = 1;
        $chart['series'][0]['data']        = $data;
        $chart['series'][0]['dataLabels']['enabled'] = true;
        $chart['series'][0]['dataLabels']['color']   = '#000000';

        $json = json_encode($chart, JSON_NUMERIC_CHECK);
        echo str_replace(['")','("'], ['',''],$json);
    }

    /******************************************************************************************************
    *         
    *           FIXED PLACEMENT CHART - GRAFICO DE POSICIONAMIENTO FIJO
    *  
    *******************************************************************************************************/

    /*
     chart: {
    type: 'column'
    },
    title: {
        text: 'Efficiency Optimization by Branch'
    },
    xAxis: {
        categories: [
            'Seattle HQ',
            'San Francisco',
            'Tokyo'
        ]
    },
    yAxis: [{
        min: 0,
        title: {
            text: 'Employees'
        }
    }, {
        title: {
            text: 'Profit (millions)'
        },
        opposite: true
    }],
    legend: {
        shadow: false
    },
    tooltip: {
        shared: true
    },
    plotOptions: {
        column: {
            grouping: false,
            shadow: false,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Employees',
        color: 'rgba(165,170,217,1)',
        data: [150, 73, 20],
        pointPadding: 0.3,
        pointPlacement: -0.2
    }, {
        name: 'Employees Optimized',
        color: 'rgba(126,86,134,.9)',
        data: [140, 90, 40],
        pointPadding: 0.4,
        pointPlacement: -0.2
    }, {
        name: 'Profit',
        color: 'rgba(248,161,63,1)',
        data: [183.6, 178.8, 198.5],
        tooltip: {
            valuePrefix: '$',
            valueSuffix: ' M'
        },
        pointPadding: 0.3,
        pointPlacement: 0.2,
        yAxis: 1
    }, {
        name: 'Profit Optimized',
        color: 'rgba(186,60,61,.9)',
        data: [203.6, 198.8, 208.5],
        tooltip: {
            valuePrefix: '$',
            valueSuffix: ' M'
        },
        pointPadding: 0.4,
        pointPlacement: 0.2,
        yAxis: 1
    }]
     */

    public static function genFixedPlacementChart($title, $getData)
    {
        $xCategories = $getData['xGroups'];
        $yCategories = $getData['yGroups'];
        $data        = $getData['data'];

        $chart = array(); 

        $chart['credits']['enabled']                    = null;
        $chart['exporting']['enabled']                  = true;

        $chart['chart']['type']                         = 'column';
        $chart['chart']['plotBorderWidth']              = 1;
        $chart['chart']['marginTop']                    = 20;
        $chart['chart']['marginBottom']                 = 130;    

        // Title
        $chart['title']['text']                         = null; // cambiar a titulo de eje x
        $chart['xAxis']['categories']                   = $xCategories;

        $chart['yAxis'][0]['min']                       = 0;
        $chart['yAxis'][0]['title']['text']             = null; // cambiar a titulo de eje izquierdo 
        $chart['yAxis'][1]['title']['text']             = null; // cambiar a titulo de eje derecho
        $chart['yAxis'][1]['opposite']                  = true;

        $chart['tooltip']['shared']                     = true;

        $chart['poltOptions']['column']['grouping']     = false;
        $chart['poltOptions']['column']['shadow']       = false;
        $chart['poltOptions']['column']['borderWidth']  = 0;
        
        $json = json_encode($chart, JSON_NUMERIC_CHECK);
        echo str_replace(['")','("'], ['',''],$json);
    }

    /******************************************************************************************************
    *         
    *           FUNCIONES MISCELANEAS
    *  
    *******************************************************************************************************/

    public static function genPointFormat($searchType)
    {
        $shared = true;
        $useHTML = true;
        switch($searchType){
            case 'cantidad':
                $headerFormat = '<small>{point.key}</small><table>';
                $pointFormat  = '<tr><td style="color: {series.color}">{series.name}: </td>';
                $pointFormat .= "<td style=\"text-align: right\"><b>Cantidad&nbsp;&nbsp;&nbsp;[{point.count}]</b></td></tr>";
                $footerFormat = '</table>';
                break;
            case 'nps':
                $headerFormat = '<small>{point.key}</small><table>';
                $pointFormat  = '<tr><td style="color: {series.color}">{series.name}: </td>';
                $pointFormat .= "<td style=\"text-align: right\"><b>{point.y:.2f}%&nbsp;&nbsp;&nbsp;[{point.count}]</b></td></tr>";
                $footerFormat = '</table>';
                break;
            case 'igual':
                $headerFormat = '<small>{point.key}</small><table>';
                $pointFormat  = '<tr><td style="color: {series.color}">{series.name}: </td>';
                $pointFormat .= "<td style=\"text-align: right\"><b>{point.y}&nbsp;&nbsp;&nbsp;[{point.count}]</b></td></tr>";
                $footerFormat = '</table>';
                break;
            case 'distinto':
                $headerFormat = '<small>{point.key}</small><table>';
                $pointFormat  = '<tr><td style="color: {series.color}">{series.name}: </td>';
                $pointFormat .= '<td style="text-align: right"><b>{point.y}&nbsp;&nbsp;&nbsp;[{point.count}]</b></td></tr>';
                $footerFormat = '</table>';
                break;
            case 'porcentaje':
                $headerFormat = '<small>{point.key}</small><table>';
                $pointFormat  = '<tr><td style="color: {series.color}">{series.name}: </td>';
                $pointFormat .= "<td style=\"text-align: right\"><b>{point.y:.2f}%&nbsp;&nbsp;&nbsp;[{point.count}]</b></td></tr>";
                $footerFormat = '</table>';
                break;
            case 'promedio':
                $headerFormat = '<small>{point.key}</small><table>';
                $pointFormat  = '<tr><td style="color: {series.color}">{series.name}: </td>';
                $pointFormat .= '<td style="text-align: right"><b>{point.y:.2f}&nbsp;&nbsp;&nbsp;[{point.count}]</b></td></tr>';
                $footerFormat = '</table>';
                break;
            case 'mayor':
                $headerFormat = '<small>{point.key}</small><table>';
                $pointFormat  = '<tr><td style="color: {series.color}">{series.name}: </td>';
                $pointFormat .= '<td style="text-align: right"><b>{point.y</b>}&nbsp;&nbsp;&nbsp;[{point.count}]</b></td></tr>';
                $footerFormat = '</table>';
                break;
            case 'menor':
                $headerFormat = '<small>{point.key}</small><table>';
                $pointFormat  = '<tr><td style="color: {series.color}">{series.name}: </td>';
                $pointFormat .= '<td style="text-align: right"><b>{point.y}&nbsp;&nbsp;&nbsp;[{point.count}]</b></td></tr>';
                $footerFormat = '</table>';
                break;
            case 'suma':
                $headerFormat = '<small>{point.key}</small><table>';
                $pointFormat  = '<tr><td style="color: {series.color}">{series.name}: </td>';
                $pointFormat .= '<td style="text-align: right"><b>{point.y}&nbsp;&nbsp;&nbsp;[{point.count}]</b></td></tr>';
                $footerFormat = '</table>';
                break;
        }
        return array(
            'shared'       => $shared,
            'useHTML'      => $useHTML,
            'headerFormat' => $headerFormat, 
            'pointFormat'  => $pointFormat, 
            'footerFormat' => $footerFormat
        );
    }

    public static function genPointName($searchType)
    {
        switch ($searchType) {
            case 'cantidad':
                return 'Cantidad';
            case 'nps':
                return 'Nps';
            case 'igual':
                return 'Igual';
            case 'distinto':
                return 'Distinto';
            case 'porcentaje':
                return 'Porcentaje';
            case 'promedio':
                return 'Promedio';
            case 'mayor':
                return 'Mayor a';
            case 'menor':
                return 'Menor a';
            case 'suma':
                return 'Suma';
        }
    }

    public static function setBarColor($metroColor)
    {
        switch ($metroColor) {
            case 'lime':
                return 'a4c400';
            case 'green':
                return '60a917';
            case 'emerald':
                return '008a00';
            case 'cyan':
                return '1ba1e2';
            case 'cobalt':
                return '0050ef';
            case 'indigo':
                return '6a00ff';
            case 'violet':
                return 'aa00ff';
            case 'pink':
                return 'f472d0';
            case 'magenta':
                return 'd80073';
            case 'crimson':
                return 'a20025';
            case 'red':
                return 'e51400';
            case 'orange':
                return 'fa6800';
            case 'amber':
                return 'f0a30a';
            case 'yellow':
                return 'e3c800';
            case 'brown':
                return '825a2c';
            case 'olive':
                return '6d8764';
            case 'steel':
                return '647687';
            case 'mauve':
                return '76608a';
            case 'taupe':
                return 'bc987e';
            case 'sienna':
                return '6d8764';
            case 'gray':
                return 'cbcbc9';
            case 'blue':
                return '2d89ef';
        }
    }

}
?>
