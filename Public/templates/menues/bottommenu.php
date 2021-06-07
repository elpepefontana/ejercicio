<?php
    
    use System\Core\Controls as Controls;
    
    $session = new \System\Core\Session();
    $menu = new \System\Core\Menu($session);
    $json = $menu->genMenu('configmenu'); 
    $menu = Controls::drawMenu('t-menu compact horizontal fixed-bottom place-right', $json,"data-role=\"collapse\" data-toggle-element=\"#btn_bottom\"", false, false);
    $menuDiv =  Controls::drawDiv("div_bottommenu", "fixed-bottom place-right", $menu, '');
    $button = Controls::drawButton('btn_bottom', "button bg-lightBlue fg-white", '...', '');
    $buttonDiv =  Controls::drawDiv("div_buttonmenu", "fixed-bottom place-right", $button, '');
    echo Controls::drawDiv("div_menu", "fixed-bottom", $buttonDiv . $menuDiv, '');
    
?>
