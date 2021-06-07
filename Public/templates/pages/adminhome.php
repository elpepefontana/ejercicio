<?php

    defined('BASEPATH') or exit('No se permite acceso directo');
    
    header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <link href="https://cdn.metroui.org.ua/v4/css/metro-all.min.css?ver=@@b-version" rel="stylesheet">
        <style>
            .start-screen {
                min-width: 100%;
                height: 100%;
                position: relative;
                padding-bottom: 20px;
            }
              
            .start-screen-title {
                position: fixed;
                top: 30px;
                left: 50px;
                display: none;
            }

            [class*=tile-] {
                -webkit-transform: scale(0.8);
                    -ms-transform: scale(0.8);
                        transform: scale(0.8);
            }

            .tiles-group {
                margin-left: 0;
                margin-top: 50px;
            }

            @media all and (min-width: 768px) {
                .start-screen-title {
                    display: block;
                }
                .start-screen {
                    padding: 140px 80px 0 0;
                }
                .tiles-group {
                    left: 10px;
                }
                .tiles-group {
                    margin-left: 12px;
                }
            }
        </style>

        <title><?=SITE_NAME;?></title>
    </head>
    <body class="bg-white fg-dark h-vh-100 m4-cloak">
        <div class="container-fluid start-screen h-100">    
            <h1 class="start-screen-title mb-20"><?=SITE_NAME;?></h1>
            <div class="tiles-area clear">
                <div class="tiles-area clear">
                    <div class="tiles-grid tiles-group size-2" data-group-title="Usuarios">
                        <a href="<?=HOME;?>/Users/List" id="user" data-role="tile" data-size="medium" class="bg-blue fg-white">
                            <span class="mif-user icon"></span>
                            <span class="branding-bar">Usuario</span>
                        </a>
                        <a href="<?=HOME;?>/Group/List/" id="user_group" data-role="tile" data-size="medium" class="bg-indigo fg-white">
                            <span class="mif-equalizer2 icon"></span>
                            <span class="branding-bar">Grupos de usuarios</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
        <script src="https://cdn.metroui.org.ua/v4/js/metro.min.js"></script>
        <script type="text/javascript" src='<?= HOME;?>/public/js/metro/start.js'></script>
    </body>
</html>