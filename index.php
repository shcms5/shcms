<?php
define('SHCMS_ENGINE',true);
include_once('engine/system/core.php');
//Название страницы
$templates->template($glob_core['name_site']);   


//Получаем данные по Виджетам
$widget = new \Shcms\Options\Widget\WidgetOption();

//Выводим Виджеты
$widget->listing();

//Данные о пользователей
echo $widget->statics();
