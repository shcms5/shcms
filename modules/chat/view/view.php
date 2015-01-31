<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}
//Автоматическая загрузка классов 
function autoloadController($className) {
    $filename = H.'modules/chat/controller/' . $className . '.php';
    if (is_readable($filename)) {
        include_once $filename;
    }
}
//Объявляем все классы
spl_autoload_register("autoloadController");

$controller = new controller;
$removal = new removal;