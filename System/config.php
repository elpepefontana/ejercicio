<?php
defined('BASEPATH') or exit('No se permite acceso directo');

/////////////////////////////////
////// valores URI
define('URI', $_SERVER['REQUEST_URI']);
define('REQUEST_METHOD',$_SERVER['REQUEST_METHOD']);

define('USER_IP', $_SERVER['REMOTE_ADDR']);

///////// Valores de Bases de datos - desarrollo
define('DB_HOST', 'localhost');
define('DB_NAME', 'ejercicio');
define('DB_DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_TYPE', 'MySql'); //MySql

define('DB_RENAME_TABLE', false);
define('DB_RENAME_COLUMN', false);

define('DB_DROP_TABLE', false);
define('DB_DROP_COLUMN', false);


///////// Valores de Login 
define('ERROR_REPORTING_LEVEL', -1);
define('TIMEOUT', 9000); //

///////// COOKIES 
define('COOKIE_DATA', array(
    'cookie_expire' => strtotime('+30 days'),
    'cookie_prefix' => '',
    'cookie_path'   => '',
    'cookie_domain' => '',
    'cookie_secure' => true,
    'cookie_httponly' => true
));

define('LOGIN_SWITCH', true); // CUIDADO PUEDE TRAR PROBLEMAS AL NO ESTAR SETEADOS LOS DATOS DEL USUARIO EN LA SESSION
define('MODULE_SWITCH', false);
   

///////// SERVER DATA - VALORES DE DATOS DEL SERVER
define('ROOT', $_SERVER['DOCUMENT_ROOT']);
define('HOST', $_SERVER['HTTP_HOST']);

define('DATA_HOLDER', 'array'); // array, object

///////// BASIC FRAMEWORK PATH VALUES - RUTAS BASICAS DEL FRAMEWORK

define('HOME', '/Ejercicio' );
define('SYSTEM_PATH' , ROOT . HOME . '/System/');
define('CORE_PATH'   , ROOT . HOME . '/System/Core/');
define('HELPERS_PATH', ROOT . HOME . '/System/Helpers/');


///////// FRAMEWORK PATHS

define('MODEL_PATH', ROOT . HOME . '/App/Models/');
define('CONTROLLER_PATH', ROOT . HOME . '/App/Controllers/');
define('VIEW_PATH', ROOT . HOME . '/App/Views/');

define('TEMPLATE_PATH', ROOT . HOME . '/Public/templates/pages/');

define('CONTROLS_PATH', ROOT . HOME . '/Public/templates/pages/controls/');

define('INPUT_PATH', ROOT . HOME . '/Public/templates/pages/inputs/');

define('CSS_PATH', ROOT . HOME . '/Public/css/pages/');

define('JS_PATH', ROOT . HOME . '/Public/js/pages/');

///////// RESOURCES PATH CONFIG - VALORES DE CONFIGURACION DE RECURSOS

define('PUBLIC_PATH', ROOT . HOME . '/Public/');

define('LIBRARY_PATH', '/Public/');

define('IMAGES_PATH' , HOME . '/Public/images/');
define('GALLERY_PATH', IMAGES_PATH . 'Gallery/');
define('IMAGES_RESIZE', true);
define('IMAGES_HEIGHT', 1800);
define('IMAGES_WIDTH', 1200);
define('IMAGES_RESAMPLE', true);
define('IMAGES_QUALITY', 90);
define('IMAGES_ACCESS', false);

define('RENDERER_PATH', SYSTEM_PATH . 'Renderer/');
define('CONTROL_RENDERER_PATH', RENDERER_PATH . 'ControlRenderer/');
define('INPUT_RENDERER_PATH', RENDERER_PATH . 'InputRenderer/');
define('ELEMENT_RENDERER_PATH', RENDERER_PATH . 'ElementRenderer/');

// DEBUG QUERYS

define('DEBUG_QUERYS', true);

// LANGUAGES CONFIG VALUES - VALORES DE CONFIGURACION DE IDIOMAS

define('LANG_SWITCH', false);
define('LANG_DEFAULT', 'es_AR');
define('LANG_PATH', ROOT . HOME . '/Public/lang/');


// VIEW CONFIG VALUES - VALORES DE CONFIGURACION DE VISTAS

define('SITE_NAME', 'Ejercicio');

define('VIEW_BG_COLOR', 'white' );
define('VIEW_FG_COLOR', 'dark' );

define('DEFAULT_GRID_DRAW_METHOD', 'html');
