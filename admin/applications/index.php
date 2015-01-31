<?
define('SHCMS_ENGINE',true);
define('HOME',$_SERVER['DOCUMENT_ROOT'],true);
define(CP_DERICTORY,'../');
define(ICON_ADMIN,'../icons/');

include_once(HOME.'/engine/system/core.php');
$templates->template('SHCMS: Система - Система');
echo engine::admin($users['group']);

switch($do):

default:

echo '<div class="mainpost">';
echo '<div class="subpost"><img src="'.ICON_ADMIN.'application/module.png">&nbsp;<a href="'.CP_DERICTORY.'applications/index.php?do=application">Модули Дополнительные</a></div>';
echo '</div>';

   echo engine::home(array(Lang::__('Назад'),'/admin.php')); //Переадресация

break;

    case 'application':
        include_once('application.php');
    break;
endswitch;


