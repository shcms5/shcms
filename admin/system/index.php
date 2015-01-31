<?php
define('SHCMS_ENGINE',true);
define('HOME',$_SERVER['DOCUMENT_ROOT'],true);
define(CP_DERICTORY,'../');
define(ICON_ADMIN,'../icons/');

include_once(HOME.'/engine/system/core.php');
include_once(HOME.'/admin/skins/header.php');

echo engine::admin($users['group']);

use Shcms\Component\Provider\Json\JsonScript;
$json = new JsonScript;	

switch($do):

default:
    echo '<div class="header"><h1 class="page-title">Системные настройки</h1></div>';	 
echo '<div class="widget">
                        <ul class="cards list-group not-bottom no-sides">
                            <li class="list-group-item">
                                <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-wrench pull-left text-info"></i>
                                <a href="'.CP_DERICTORY.'system/index.php?do=setting"><h4>Настройки</h4></a>
                                <p class="info small">Основные настройки скрипта SHCMS Engine</p>
                            </li>
                            <li class="list-group-item">
                                <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-cog pull-left text-info"></i>
                                <a href="'.CP_DERICTORY.'system/index.php?do=application"><h4>Приложения &rarr;</h4></a>
                                <p class="info small">Добавление удаление разделов на гл. страницу</p>
                            </li>
                            <li class="list-group-item">
                                <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-cogs pull-left text-info"></i>
                                <a href="'.CP_DERICTORY.'system/index.php?do=system"><h4>Система &rarr;</h4></a>
                                <p class="info small">Предупреждает об возможных ошибках</p>
                            </li>
                        </ul>
                </div>';

echo engine::home(array('Назад','/admin.php'));	 
break;

//Настройки
case 'setting':
include_once('setting.php');
break;


//Приложения
case 'application':
include_once('application.php');
break;


//Система
case 'system':
include_once('system.php');
break;


endswitch;

include_once(HOME.'/admin/skins/footer.php');

