# ejercicio
ejercicio

PHP version 7.2.32 (XAMPP 7.2.32)
MariaDb version 10.4.13 (XAMPP 7.2.32)

La configuración de la conexión de la base de datos se hace en el archivo System/config.php

-------------------------------------------------------------------------------------
define('DB_HOST', 'localhost'); // configurar host
define('DB_NAME', 'ejercicio'); // configurar nombre dde base de datos
define('DB_DSN', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8');
define('DB_USER', 'root'); // cambiar a usuario de conexión
define('DB_PASS', ''); // cambiar contraseña de conexión
define('DB_TYPE', 'MySql'); //MySql
-------------------------------------------------------------------------------------

Tambien hay que tocar la opción del System/config.php HOME para que use el nombre de la subcarpeta de localhost, por ejemplo si el codigo del sitio se encuantra en
.../htdocs/Ejercicio el valor de  HOMe seria '/Ejercicio' (define('HOME', '/Ejercicio').

El resto de los valores del archivo de configuracion no es necesario tocarlos

++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

El System/Core/Router esta configurado para usar un host local (ejemplo http://localhost/Ejercicio), para paginas online uso otro Router.
