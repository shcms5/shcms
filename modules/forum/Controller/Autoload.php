<?php
if( ! defined( 'SHCMS_ENGINE' ) ) {
    die('SHCMS Engine No access');
}

/*
 * Подгрузка необходимых классов
 */
$loader = new SplLoader('forum', H.'modules');
//Регистрация классов
$register = $loader->register();