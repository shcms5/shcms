<?php
define('SHCMS_ENGINE',true);
include_once('../engine/system/core.php');
$templates->template($glob_core['name_site']);   

use Shcms\Options\Widget\WidgetOption;

echo engine::AngularJS();





$userz = new Shcms\Component\Users\User\User(1);

echo $userz->id;
$nick[] = $userz->Views(array('`nick`','`id`','`group`'));

echo '<a href="'.$userz->id.'">'.$nick[0]['nick'].'('.$nick[0]['group'].')</a>';
$widget = new WidgetOption;
//Полное меню
$widget->listing();
//Пользовательская статистика.
echo $widget->statics();
