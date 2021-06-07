<!DOCTYPE html>
<html>
    <head>
        <meta name="metro4:init" content="true">
        <meta name="metro4:locale" content="es-MX">
        <meta name="metro4:week_start" content="0">
        <meta name="metro4:jquery" content="false">
        
        <!-- dashicons
        <link rel="stylesheet" href="<?= HOME;?>/public/css/jtable/dashicons.css" type="text/css" />
            
        <!-- jquery ui css -->
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        
        <!-- jtable theme -->
        <link rel="stylesheet" href="<?= HOME;?>/public/css/jtable/metro/blue/jtable.min.css" type="text/css" />
        
        <!-- metro css -->
        <link rel="stylesheet" href="/Ejercicio/public/css/metro/metro-all.min.css">
        
        <!-- jquery validation engine css -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/validationEngine.jquery.min.css" type="text/css"/>
  
        <link rel="stylesheet" href="<?= HOME;?>/public/css/general/general.css">
        
        <!-- jquery -->
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" type="text/javascript"></script>
        
        <!-- jquery ui-->
        <script type="text/javascript" src='https://code.jquery.com/ui/1.12.1/jquery-ui.min.js' defer></script>
        
        <!-- jquery ui datepicker español-->
        <script type="text/javascript" src='<?= HOME;?>/public/js/jquery-ui/datepicker-es.js' defer></script>
        
        <!-- high charts
        <script type="text/javascript" src="https://code.highcharts.com/highcharts.js" defer></script>
        -->
        <!-- jtable -->
        <script type='text/javascript' src='https://cdn.jsdelivr.net/npm/jtable@2.6.0/jquery.jtable.js' defer></script>
        <!-- jtable español -->
        <script type="text/javascript" src='<?= HOME;?>/public/js/jtable/jquery.jtable.es.js' defer></script>
        
        <!-- jquery validation engine -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/jquery.validationEngine.min.js" defer   charset="utf-8"></script>
        <!-- jquery validation engine español -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jQuery-Validation-Engine/2.6.4/languages/jquery.validationEngine-es.min.js" charset="utf-8" defer></script>
        
        <!-- general js -->
        <script src="<?= HOME;?>/public/js/general/general.js" defer></script>
        
        <!-- Metro UI js -->
        <script src="/Ejercicio/public/js/metro/metro.min.js"  type="text/javascript"></script>
        
        [[CSS]]
        
        [[JS]]
        
        <script>
            var homePath = '<?=HOME;?>';
        </script>
        <style>
            
            .login-form {
                width: 700px;
                background-color: #fa96c0;
                height: auto;
                top: 50%;
                margin-top: -160px;
            } 
            
            .Modal {
                display: none; /* Hidden by default */
                position: fixed; /* Stay in place */
                z-index: 1; /* Sit on top */
                left: 0;
                top: 0;
                width: 100%; /* Full width */
                height: 100%; /* Full height */
                overflow: auto; /* Enable scroll if needed */
                background-color: rgb(0,0,0); /* Fallback color */
                background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            }
            
            .Modal-content {
                background-color: #fefefe;
                margin: 5% auto; /* 15% from the top and centered */
                padding: 10px;
                border: 1px solid #888;
                width: 70%; /* Could be more or less, depending on screen size */
            }
            
            .ModalClose {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }
            
            .ModalClose:hover,
            .ModalClose:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }

            
            
        </style>
	<title><?=SITE_NAME;?></title>
    </head>
        <body class="h-vh-100 bg-<?=VIEW_BG_COLOR?> fg-<?=VIEW_FG_COLOR?>">    
        
        