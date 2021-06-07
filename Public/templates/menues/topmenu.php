<?php

use \System\Core\Controls as Controls;

$session = new \System\Core\Session();
$menu    = new \System\Core\Menu($session);
$json    = $menu->genMenu('topmenu');
$topMenu = Controls::drawMenu('h-menu', $json, '', true, true, false);
$nav     = Controls::drawNav('topmenu', '', $topMenu, '');
$header  = Controls::drawHeader('', '', $nav, '');
echo $header;

           
        