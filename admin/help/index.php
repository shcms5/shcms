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
    echo '<div class="header"><h1 class="page-title">Поддержка SHCMS Engine</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a></li>
            <li class="active">О системе</li>
        </ul></div>';
    
            echo '<div class="widget">
                <ul class="cards list-group not-bottom no-sides">
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-info-circle pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'help/index.php?do=about"><h4>Справка</h4></a>
                        <p class="info small">Управление пользователями</p>
                    </li>
                   <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-flag pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'help/index.php?do=shcms_money"><h4>Помощь в развитие</h4></a>
                        <p class="info small">Управление категориями предупреждений</p>
                    </li>
                </ul>
            </div>';
         
break;

//Справка
    case 'about':
        
            echo '<div class="header"><h1 class="page-title">Что такое SHCMS Engine</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a></li>
            <li><a href="index.php">О системе</a></li>            
            <li class="active">Справка</li>
        </ul></div>';
    
        include_once('help.php');
    break;

    case 'shcms_money':
        
            echo '<div class="header"><h1 class="page-title">Поддержка SHCMS Engine</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a></li>
            <li><a href="index.php">О системе</a></li>            
            <li class="active">Развитие</li>
        </ul></div>';
    
        include_once('money.php');
    break;

endswitch;

include_once(HOME.'/admin/skins/footer.php');
