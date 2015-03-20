<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

/*
 * Подгрузка необходимых классов
 */
$loader = new SplLoader('files', H.'modules');
//Регистрация классов
$register = $loader->register();