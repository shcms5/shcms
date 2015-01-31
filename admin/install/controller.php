<?php
define("H", $_SERVER["DOCUMENT_ROOT"].'/');



require_once (H.'engine/system/Autoload.php');
$loader = new SplLoader('Shcms', H.'engine/classes'); 
$loader->register();

include_once('engine/classes/engine.class.php');
//О разработчике
include_once('engine/classes/version.class.php');



if (!isset ( $step ) AND isset ($_REQUEST['step']) ) {
    $step = engine::totranslit ( $_REQUEST['step'] ); 
}elseif(isset ( $step )) {
    $step = engine::totranslit ( $step );
} else { 
    $step = "";
}        

//Включение буферизации вывода		 
@ob_start();
// Выключение неявных сбросов   
@ob_implicit_flush(0);
