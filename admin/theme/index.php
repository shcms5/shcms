<?php
define('SHCMS_ENGINE',true);
define('HOME',$_SERVER['DOCUMENT_ROOT'],true);
define(CP_DERICTORY,'../');
define(ICON_ADMIN,'../icons/');

include_once(HOME.'/engine/system/core.php');
include_once(HOME.'/admin/skins/header.php');
echo engine::admin($users['group']);

switch($do):

default:
    echo '<div class="header"><h1 class="page-title">Внешний вид</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a> </li>
            <li class="active">Вид</li>
        </ul></div>';
    
 echo '<div class="widget">
                <ul class="cards list-group not-bottom no-sides">
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-columns pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'theme/index.php?do=templates"><h4>'.Lang::__('Шаблоны сайта').'</h4></a>
                        <p class="info small">Доступные шаблоны скрипта</p>
                    </li>   
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-clipboard pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'theme/index.php?do=language"><h4>'.Lang::__('Язык').'</h4></a>
                        <p class="info small">Смена языка на сайте</p>
                    </li>   
                </ul>
            </div>'; 
break;

//Шаблоны
case 'templates':
    echo '<div class="header"><h1 class="page-title">Управление шаблонами</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a> </li>
             <li><a href="index.php">Вид</a> </li>
            <li class="active">Шаблоны</li>
        </ul></div>';
include_once('templates.php');

break;

//Язык
case 'language':
    echo '<div class="header"><h1 class="page-title">Управление мультиязычностью</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a> </li>
             <li><a href="index.php">Вид</a> </li>
            <li class="active">Язык</li>
        </ul></div>';
include_once('language.php');

break;

endswitch;


include_once(HOME.'/admin/skins/footer.php');