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
            
    echo '<div class="header"><h1 class="page-title">Пользователи</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a></li>
            <li class="active">Пользователи</li>
        </ul></div>';
    
            echo '<div class="widget">
                <ul class="cards list-group not-bottom no-sides">
                    <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-group pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'user/index.php?do=user"><h4>Пользователи &rarr;</h4></a>
                        <p class="info small">Управление пользователями</p>
                    </li>
                   <li class="list-group-item">
                        <i class="fa-2x padding-top-small padding-bottom padding-right-small fa fa-question pull-left text-info"></i>
                        <a href="'.CP_DERICTORY.'user/index.php?do=warnings"><h4>Предупреждения &rarr;</h4></a>
                        <p class="info small">Управление категориями предупреждений</p>
                    </li>
                </ul>
            </div>';
        
        break;

        //Настройки
        case 'user':
            
            echo '<div class="header"><h1 class="page-title">Пользователи</h1>';
            echo '<ul class="breadcrumb">
                  <li><a href="../index.php">Настройки</a></li>
                  <li><a href="index.php">Пользователи</a></li>
                  <li class="active">Управление</li>
            </ul></div>';
            include_once('user.php');

        break;

        //Предупреждения пользователям
        case 'warnings':

		    include_once('warnings.php');

		break;
		
		case 'ban':

		    include_once('ban.php');
			
        break;

endswitch;


include_once(HOME.'/admin/skins/footer.php');