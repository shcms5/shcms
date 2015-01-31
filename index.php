<?php
define('SHCMS_ENGINE',true);
include_once('engine/system/core.php');
$templates->template($glob_core['name_site']);   

use Shcms\Options\Widget\WidgetOption;

echo engine::AngularJS();

//

$widget = new WidgetOption;
//Полное меню
$widget->listing();
//Пользовательская статистика.
echo $widget->statics();
