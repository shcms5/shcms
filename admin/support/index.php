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
    echo '<div class="header"><h1 class="page-title">Поддержка</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a> </li>
            <li class="active">Поддержка</li>
        </ul></div>';
    
 echo '<div class="widget">
                <ul class="cards list-group not-bottom no-sides">
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-bar-chart-o pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'support/index.php?do=diagnostic"><h4>'.Lang::__('Диагностика &rarr;').'</h4></a>
                        <p class="info small">Инфорамация о SHCMS Engine и о ваших данных</p>
                    </li>   
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-random pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'support/index.php?do=sql"><h4>'.Lang::__('Управление SQL &rarr;').'</h4></a>
                        <p class="info small">Легкое управление базой данных</p>
                    </li>   
                </ul>
            </div>';

echo engine::home(array('Назад','/admin.php'));	 
break;

//Диагностика
case 'diagnostic':
    echo '<div class="header"><h1 class="page-title">Системный обзор</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li class="active">Системный обзор</li>
        </ul></div>';
    
include_once('diagnostics.php');

break;


//Управление SQL
case 'sql':
        echo '<div class="header"><h1 class="page-title">Работа с базой данных</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="index.php">Назад</a> </li>
            <li class="active">Управление SQL</li>
        </ul></div>';
include_once('toolbar_sql.php');
break;

endswitch;


include_once(HOME.'/admin/skins/footer.php');