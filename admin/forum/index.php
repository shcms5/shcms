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
    echo '<div class="header"><h1 class="page-title">Прикрепленные файлы</h1>';
    echo '<ul class="breadcrumb">
            <li><a href="../index.php">Назад</a> </li>
            <li class="active">Файлы в форуме</li>
        </ul></div>';
    include_once('forumtype.php');

break;



endswitch;

include_once(HOME.'/admin/skins/footer.php');

