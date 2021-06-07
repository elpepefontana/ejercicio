<!DOCTYPE html>
<html>
    <head>
        <meta name="metro4:init" content="true">
        <meta name="metro4:locale" content="es-MX">
        <meta name="metro4:week_start" content="0">
        <meta name="metro4:jquery" content="false">
        
        <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
        
        <!-- Include one of jTable styles. -->
        <link href="/jtable/themes/metro/blue/jtable.min.css" rel="stylesheet" type="text/css" />

        <!-- Include jTable script file. -->
        <script src="/jtable/jquery.jtable.min.js" type="text/javascript"></script>
        
        [[CSS]]
        
        [[JS]]
        
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
        
        <script>
            homePath = '<?=HOME;?>';
        </script>
        
	<title><?=SITE_NAME;?></title>
    </head>
        <body class="h-vh-100 bg-<?=VIEW_BG_COLOR?> fg-<?=VIEW_FG_COLOR?>">
        
        