<?php

use \System\Core\Controls as Controls;

defined('BASEPATH') or exit('No se permite acceso directo');

require_once TEMPLATE_PATH . '../headers/adminheader.php';

?>

<div class="mb-10">
<header  class="" >
<nav id="topmenu" class="text-center" >
    <ul class="h-menu  bg-darkBlue fg-white py-2 px-2"  >
        <li><a href="<?=HOME;?>/AdminHome"><span class="icon mif-home fg-white" ></span></a></li>
        <li>    
            <a href="#" class="dropdown-toggle">Usuarios</a>
            <ul class="d-menu bg-darkBlue fg-white" data-role="dropdown">
                <li><a href="<?=HOME;?>/Users/List"><span class="icon mif-user fg-white"></span>&nbsp;&nbsp;Usuario</a></li>
                <li><a href="<?=HOME;?>/Group/List"><span class="icon mif-equalizer2 fg-white"></span>&nbsp;&nbsp;Grupos de usuarios</a></li>
            </ul>
        </li>
    </nav>

</header>
</div>

[[MAIN]]

<footer class="grid h-25 bg-darkCobalt fg-white pos-center"> 
    <div class="row">
        <div class="cell-md-12 text-center">Ejercicio</div>
    </div>
    <div class="row">
        <div class="cell-md-12 text-center">José Manuel Fontana</div>
    </div>
    <div class="row">
        <div class="cell-md-12 text-center">elpepefontana@gmail.com</div>
    </div>
    <div class="row">
        <div class="cell-md-12 text-center">PHP 7.2.32</div>
    </div>
    <div class="row">
        <div class="cell-md-12 text-center">MariaDB 10.4.13</div>
    </div>
    <div class="row">
        <div class="cell-md-12 text-center"><a href="https://metroui.org.ua/" style="">METRO UI CSS</a> | <a href="https://jtable.org/">JTable</a></div>
    </div>
</footer>

<?php require_once TEMPLATE_PATH . '../footers/footer.php'; ?>