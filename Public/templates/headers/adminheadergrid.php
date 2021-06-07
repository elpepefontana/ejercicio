<!DOCTYPE html>
<html>
    <head>
        <meta name="metro4:init" content="true">
        <meta name="metro4:locale" content="es-MX">
        <meta name="metro4:week_start" content="0">
        <meta name="metro4:jquery" content="false">
        
        <!-- dashicons -->
        <link rel="stylesheet" href="<?= HOME;?>/public/css/jtable/dashicons.css" type="text/css" />
        
        <!-- metro css -->
        <link rel="stylesheet" href="<?= HOME;?>/public/css/metro/metro-all.min.css">
        
        <!-- general.css -->
        <link rel="stylesheet" href="<?= HOME;?>/public/css/general/general.css">
        
        
        <!-- Metro UI js -->
        <script src="<?= HOME;?>/public/js/metro/metro.min.js"  type="text/javascript"></script>
        
        <!-- general js -->
        <script src="<?= HOME;?>/public/js/general/general.js" defer></script>
        
        <!-- DomManipulation js -->
        <script src="<?= HOME;?>/public/js/general/dom.js" defer></script>
        
        <!-- table js -->
        <script src="<?= HOME;?>/public/js/general/table.js" defer></script>
        
        <!-- AdminGridActions js -->
        <script src="<?= HOME;?>/public/js/general/grid.js" defer></script>
        
        <!-- formFactory js -->
        <script src="<?= HOME;?>/public/js/general/form.js" defer></script>
        
        
        
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
        
        