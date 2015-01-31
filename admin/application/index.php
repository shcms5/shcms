<?
define('SHCMS_ENGINE',true);
define('HOME',$_SERVER['DOCUMENT_ROOT'],true);
define(CP_DERICTORY,'../');
define(ICON_ADMIN,'../icons/');

include_once(HOME.'/engine/system/core.php');
$templates->template('SHCMS: Система - Другие приложение');
echo engine::admin($users['group']);

switch($do):

    default:

    echo '<div class="mainpost">';
        echo '<div class="subpost"><img src="'.ICON_ADMIN.'application/filter.png">&nbsp;<a href="'.CP_DERICTORY.'application/index.php?do=block">Фильтр IP,Логин</a></div>';
    echo '</div>';

   echo engine::home(array(Lang::__('Назад'),'/admin.php')); //Переадресация

    break;


    case 'block':
        include_once('block.php');
    break;

endswitch;
?>