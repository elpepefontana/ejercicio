<?php
$menu = new Menu();
$json = $menu->genMenu('usermenu');
echo Controls::drawMenu('h-menu', $json, true, true);
?>

           
    