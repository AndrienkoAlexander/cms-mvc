<?php

require( "config.php" );
session_start();
// подключаем ядро сайта
include (SITE_PATH . DS . 'core' . DS . 'core.php');
// Загружаем router
$router = new Router();
// задаем путь до папки контроллеров.
$router->setPath (SITE_PATH . 'controllers');
// запускаем маршрутизатор
$router->start();

//$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

?>
